<?php

namespace househub\access\strategies\files;

use househub\images\dao\ImageStructureDAO;

use househub\readers\SystemReader;

use househub\users\rights\UserImages;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

class ImageRemoveAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
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
				
				$dao = new ImageStructureDAO($driver);
				$image = $dao->load($parameters['image']);
				
				if(!is_null($image)){
						$unlinked = $image->getResource();
						$unlinkedPath = StrOpers::strFixPath($uploadsPath.'/'.$unlinked);
					if($dao->delete($image) > 0){
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