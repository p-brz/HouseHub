<?php
// COMPLETE!
namespace househub\access\strategies\object;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\intents\home\HomeIntentManager;
use househub\objects\home\HomeObjectManager;
use househub\objects\home\JsonToHomeObjectParser;
use househub\users\session\SessionManager;
use lightninghowl\utils\url\UrlHandler;

class ValidateAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else if($userId != 0){
			$answer->setMessage('@forbidden');
			
		}else if(!isset($parameters['object_intent'])){
			$answer->setMessage('@bad_parameters');
		
		}else{
			$objectId = $parameters['object_intent'];
			$intentManager = new HomeIntentManager();
			$intent = $intentManager->loadIntent($objectId, $driver);
			
			$intentStructure = $intent->getStructure();
			if(is_null($intentStructure)){
				$answer->setMessage('@object_not_found');
				
			}else if($intentStructure->getParentId() != 0){
				$answer->setMessage('@direct_validate_fail');
				
			}else{
				// Now let's save the objects
				$address = $intentStructure->getAddress();
				$handler = new UrlHandler('http://'.$address);
				$handler->run();
				
				$jsonStructure = $handler->getContent();
				$json = json_decode($jsonStructure, true);
				
				if(!is_null($json)){
					// Saving the object
					$parser = new JsonToHomeObjectParser();
					$homeObject = $parser->parse($json, $address);
					
					$manager = new HomeObjectManager();
					$manager->saveObject($homeObject, $driver);
					
					$intentManager->deleteIntent($intent, $driver);
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
				}else{
					$answer->setMessage('@offline_object');
					
				}
			}
		}
		
		return $answer;
		
	}
	
}

?>