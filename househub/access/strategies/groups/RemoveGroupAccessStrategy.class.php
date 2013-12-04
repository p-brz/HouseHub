<?php
// Complete!
namespace househub\access\strategies\groups;

use lightninghowl\utils\StrOpers;

use househub\users\rights\UserGroups;

use househub\groups\home\HomeGroupManager;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use househub\access\strategies\AbstractAccessStrategy;

use PDO;

class RemoveGroupAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
			
		}else{
			$groups = new UserGroups($userId, $driver);
			
			if(!isset($parameters['group'])){
				$answer->setMessage('@bad_parameters');
				
			}else{
				$groupId = intval($parameters['group']);
				$manager = new HomeGroupManager();
				$group = $manager->loadGroup($groupId, $userId, $driver);
				
				if(is_null($group)){
					$answer->setMessage('@group_not_found');
					
				}else if(!$groups->verifyRights($parameters['group'])){
					$answer->setMessage('@forbidden');
				
				}else if($group->getUserId() == 0 && $userId != 0){
					$answer->setMessage('@forbidden');
					
				}else{
					$manager->deleteGroup($group);
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
				}
				
			}
		}
		
		return $answer;
		
	}
	
}

?>