<?php

// COMPLETE!!

namespace househub\access\strategies\groups;

use lightninghowl\utils\StrOpers;

use househub\users\rights\UserGroups;

use househub\groups\home\HomeGroupJsonParser;

use househub\groups\home\HomeGroupManager;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use househub\access\strategies\AbstractAccessStrategy;

class GatherGroupAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$sessManager->startSession();
		$userId = $sessManager->getSessionVariable('user_id');
		
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permission = new UserGroups($userId, $driver);
			
			if(!isset($parameters['group'])){
				$answer->setMessage('@group_bad_parameters');
			}else{
				$groupId = $parameters['group'];
				
				$manager = new HomeGroupManager();
				$group = $manager->loadGroup($groupId, $userId, $driver);
				
				if(is_null($group)){
					$answer->setMessage('@group_not_found');
					
				}else if(!$permission->verifyRights($groupId)){
					$answer->setMessage('@group_forbidden');
					
				}else{
					$groupParser = new HomeGroupJsonParser();
					$groupJson = $groupParser->parse($group);
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
					$answer->setContent($groupJson);
				}
			}
		}
		
		return $answer;
		
	}
	
}

?>