<?php
// Complete
namespace househub\access\strategies\groups;

use househub\groups\dao\GroupElementDAO;

use househub\groups\GroupElement;

use lightninghowl\utils\sql\InsertQuery;

use househub\groups\GroupVisual;

use househub\groups\dao\GroupVisualDAO;

use househub\users\rights\UserViews;

use househub\groups\dao\GroupStructureDAO;

use househub\groups\GroupStructure;

use househub\users\session\SessionManager;

use househub\access\strategies\AbstractAccessStrategy;

use househub\access\DatabaseConnector;

use PDO;

class AddGroupAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		$driver = DatabaseConnector::getDriver();
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else if(!isset($parameters['group_name']) || !isset($parameters['objects'])){
			$answer->setMessage('@add_category_bad_parameters');
		}else if(!is_array($parameters['objects'])){
			$answer->setMessage('@add_category_not_collection');
		}else{
			$groupDAO = new GroupStructureDAO($driver);
			$group = new GroupStructure();
			$group->setUserId($userId);
			$groupId = $groupDAO->insert($group);
			
			$groupVisualDAO = new GroupVisualDAO($driver);
			$groupVisual = new GroupVisual();
			$groupVisual->setUserId($userId);
			$groupVisual->setGroupId($groupId);
			
			$groupName = isset($parameters['group_name']) ? urldecode($parameters['group_name']) : 'Grupo';
			$groupVisual->setGroupName($groupName);
			
			$groupImage = isset($parameters['group_image']) ? $parameters['group_image'] : 0;
			$groupVisual->setGroupImageId($groupImage);
			$groupVisualDAO->insert($groupVisual);
			
			$permissions = new UserViews($userId, $driver);
			$elementDAO = new GroupElementDAO($driver);
			foreach($parameters['objects'] as $objectId){
				if($permissions->verifyRights($objectId)){
					$element = new GroupElement();
					$element->setGroupId($groupId);
					$element->setObjectId($objectId);

					$elementDAO->insert($element);
				}
			}
			
			$answer->setStatus(1);
			$answer->setMessage('@success');
		}
		return $answer;
	}
	
	
	private function querifyObject($groupId, $objectId){
		$insertQuery = new InsertQuery();
		$insertQuery->setEntity('groups_elements');
		$insertQuery->setRowData('group_id', intval($groupId));
		$insertQuery->setRowData('object_id', intval($objectId));
		
		return $insertQuery;
	}
} 

?>