<?php
// COMPLETE!
namespace househub\access\strategies\object;

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

use lightninghowl\utils\StrOpers;

use househub\users\rights\UserViews;

use househub\objects\home\HomeObjectJsonParser;

use househub\objects\home\HomeObjectManager;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use househub\access\strategies\AbstractAccessStrategy;

class GatherObjectAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
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
				
				$loader = new HomeObjectManager();
				$object = $loader->loadObject($objectId, $userId, $driver);
				if(!is_null($object)){
					$objectParser = new HomeObjectJsonParser();
					$objectJson = $objectParser->parse($object, true);
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
					$answer->setContent($objectJson);
					
				}else{
					$answer->setMessage('@gather_object_error_2');
				}	
			}
		}
		
		return $answer;
		
	}
	
}

?>