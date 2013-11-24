<?php

namespace Core\Access\Strategies\Object;

use LightningHowl\Utils\StrOpers;

use Core\Structures\BlockObject;

use Core\DAO\BlockObjectDAO;

use Core\User\SessionManager;

use Core\Access\DatabaseConnector;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

class ValidateAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		
		$answer = $this->initializeAnswer();
		
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId) || $userId != 0){
			$answer->setMessage('@forbidden');
			
		}else if(!isset($parameters['object'])){
			$answer->setMessage('@bad_parameters');
		
		}else{
			$dao = new BlockObjectDAO($driver);
			$object = $dao->getBlockObject($parameters['object']);
			if(is_null($object)){
				$answer->setMessage('@object_not_found');
				
			}else{
				$object->setValidated(1);
				$dao->update($object);
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		return $answer;
		
	}
	
	private function validate(){
		
	}
	
}

?>