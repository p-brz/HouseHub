<?php

namespace Core\Access\Strategies\Files;

use LightningHowl\Utils\StrOpers;

use Core\Images\BlockImage;

use Core\Images\BlockImageDAO;

use Core\Reader\SystemReader;

use Core\Access\DatabaseConnector;

use Core\User\Rights\UserImages;

use Core\User\SessionManager;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

class ImageRemoveAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		$userId = 1;
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permissions = new UserImages($userId, $driver);
			if(!isset($parameters['image'])){
				$answer->setMessage('@bad_parameters');
			}else if(!$permissions->verifyRights($parameters['image'])){
				$answer->setMessage('@forbidden');
			}else{
				$sysRes = SystemReader::getInstance();
				$uploadsPath = $_SERVER['DOCUMENT_ROOT'].'/'.$sysRes->translate(SystemReader::INDEX_ROOTPATH).'/'.$sysRes->translate(SystemReader::INDEX_UPLOADS);
				
				$dao = new BlockImageDAO($driver);
				$image = $dao->getBlockImage($parameters['image']);
				
				if(!is_null($image)){
						$unlinked = $image->getResource();
						$unlinkedPath = StrOpers::strFixPath($uploadsPath.'/'.$unlinked);
					if($dao->remove($image) > 0){
						unlink($unlinkedPath);
						
						$answer->setStatus(1);
						$answer->setMessage('@success');
					}
				}
			}
		}
		
		return $answer;
		
	}
	
}

?>