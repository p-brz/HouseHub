<?php

namespace Core\Access\Strategies\Files;

use househub\images\dao\ImageStructureDAO;

use househub\images\ImageStructure;

use Core\Access\DatabaseConnector;

use Core\Images\BlockImageDAO;

use LightningHowl\Utils\StrOpers;

use Core\Images\BlockImage;

use LightningHowl\Utils\Sql\InsertQuery;

use Core\User\SessionManager;

use Core\Reader\SystemReader;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

class ImageUploadAccessStrategy extends AbstractAccessStrategy{
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			if(!isset($_FILES['image'])){
				$answer->setMessage('@no_file');
			}else if(!isset($parameters['image_name'])){
				$answer->setMessage('@bad_parameters');
			}else{
				
				$imgName = $parameters['image_name'];
				if(strlen($imgName) > 20){
					$answer->setMessage('@image_name_too_long');
				}else{
					
					if(!preg_match('/^.*\.(jpg|jpeg|png|gif)$/i', $_FILES['image']['name'])){
						$answer->setMessage('@bad_image_type');
					}else if($_FILES['image']['size'] > 2097152){
						$answer->setMessage('@big_file');
					}else{
						$path_parts = pathinfo($_FILES["image"]["name"]);
						$extension = $path_parts['extension'];
						$saveName = $imgName.date('ymd_his').'.'.$extension;
						
						$sysRes = SystemReader::getInstance();
						$path = $sysRes->translate(SystemReader::INDEX_ROOTPATH).'/'.$sysRes->translate(SystemReader::INDEX_UPLOADS);
						
						$savePath = StrOpers::strFixPath($_SERVER['DOCUMENT_ROOT'].'/'.$path.'/'.$saveName);
						
						if(move_uploaded_file($_FILES['image']['tmp_name'], $savePath)){
							if(!isset($parameters['personal'])){
								$userId = 0;
							}
							$image = new ImageStructure();
							$image->setUserId($userId);
							$image->setResourcee($saveName);
							$image->setName($imgName);
							
							
							$dao = new ImageStructureDAO(DatabaseConnector::getDriver());
							if($dao->insert($image) > 0){
								$answer->setStatus(1);
								$answer->setMessage('@success');
							}
						}else{
							$answer->setMessage('@cannot_save');
						}
					}
				}
				
			}
		}
		
		
		return $answer;
		
	}
	
}

?>