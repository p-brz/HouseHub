<?php

// Complete

namespace househub\access\strategies\groups;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\groups\dao\GroupElementDAO;
use househub\groups\dao\GroupStructureDAO;
use househub\groups\dao\GroupVisualDAO;
use househub\groups\GroupElement;
use househub\groups\GroupStructure;
use househub\groups\GroupVisual;
use househub\users\rights\UserViews;
use househub\users\session\SessionManager;

class AddGroupAccessStrategy extends AbstractAccessStrategy {
    const GROUP_NAME_ARG = 'group_name';
    const OBJECTS_ARG = 'objects';
    
    private $dbDriver;
    
    public function __construct($driver = null) {
        $this->dbDriver = (!is_null($driver)? $driver : DatabaseConnector::getDriver());
    }
    
    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();

        $sessManager = SessionManager::getInstance();
        $userId = $sessManager->getSessionVariable('user_id');
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
        } else if (!isset($parameters[self::GROUP_NAME_ARG]) || !isset($parameters[self::OBJECTS_ARG])) {
            $answer->setMessage('@add_category_bad_parameters');
        } else if (!is_array($parameters[self::OBJECTS_ARG])) {
            $answer->setMessage('@add_category_not_collection');
        } else {
            $this->addGroup($parameters, $userId, $answer);
        }
        return $answer;
    }

    protected function addGroup($parameters, $userId, $answer) {
        $driver = $this->dbDriver;
        
        $groupId = $this->saveGroupStructure($userId, $driver);
        $this->saveGroupVisual($parameters, $userId, $groupId, $driver);
        $this->saveGroupElements($parameters, $userId, $groupId, $driver);

        $answer->setStatus(1);
        $answer->setMessage('@success');
    }
    
    protected function  saveGroupStructure($userId, $driver){
        $groupDAO = new GroupStructureDAO($driver);
        $group = new GroupStructure();
        $group->setUserId($userId);
        $groupId = $groupDAO->insert($group);
        
        return $groupId;
    }


    protected function saveGroupVisual($parameters, $userId, $groupId, $driver){
        $groupVisualDAO = new GroupVisualDAO($driver);
        $groupVisual = new GroupVisual();
        $groupVisual->setUserId($userId);
        $groupVisual->setGroupId($groupId);
        
        $groupName = isset($parameters[self::GROUP_NAME_ARG]) ? urldecode($parameters[self::GROUP_NAME_ARG]) : 'Grupo';
        $groupVisual->setGroupName($groupName);

        $groupImage = isset($parameters['group_image']) ? $parameters['group_image'] : 0;
        $groupVisual->setGroupImageId($groupImage);
        $groupVisualDAO->insert($groupVisual);
    }
    
    protected function saveGroupElements($parameters, $userId, $groupId, $driver){
        $permissions = new UserViews($userId, $driver);
        $elementDAO = new GroupElementDAO($driver);
        foreach ($parameters[self::OBJECTS_ARG] as $objectId) {
            if ($permissions->verifyRights($objectId)) {
                $element = new GroupElement();
                $element->setGroupId($groupId);
                $element->setObjectId($objectId);

                $elementDAO->insert($element);
            }
        }
    }

//	private function querifyObject($groupId, $objectId){
//		$insertQuery = new InsertQuery();
//		$insertQuery->setEntity('groups_elements');
//		$insertQuery->setRowData('group_id', intval($groupId));
//		$insertQuery->setRowData('object_id', intval($objectId));
//		
//		return $insertQuery;
//	}
}

?>