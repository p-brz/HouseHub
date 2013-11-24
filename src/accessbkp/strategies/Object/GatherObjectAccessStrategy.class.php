<?php

namespace Core\Access\Strategies\Object;

/**
 * Acquires informations about objects
 * 
 * Seleciona um objeto e então o retorna como json
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 *
 */
use Core\Access\Strategies\AbstractAccessStrategy;

use Core\User\Rights\UserViews;

use Core\User\SessionManager;

use LightningHowl\Utils\StrOpers;

use Core\Answer\AnswerEntity;

use Core\Loaders\ObjectLoader;

use Core\Answer\JsonAnswerParser;

use Core\Parsers\ObjectJsonParser;

use Core\Access\DatabaseConnector;


class GatherObjectAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = new AnswerEntity();
		$answer->setStatus(0);
		$answer->setMessage('@error');
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$sessManager->startSession();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
			
		}else{
			$permission = new UserViews($userId, $driver);
			
			if(!isset($parameters['object'])){
				$answer->setMessage("@gather_object_error_1");
				
			}else if(!$permission->verifyRights($parameters['object'])){
				$answer->setMessage('@forbidden');
				
			}else{
				$objectId = $parameters['object'];
				
				$loader = new ObjectLoader();
				$object = $loader->load($objectId, $driver);
				
				if(!is_null($object)){
					$objectParser = new ObjectJsonParser();
					$objectJson = $objectParser->objectToJson($object, $userId);
					$objectJson->setName("object");
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
					$answer->addContent($objectJson);
					
				}else{
					$answer->setMessage('@gather_object_error_2');
				}	
			}
		}
		
		return $answer;
		
	}
	
}

?>