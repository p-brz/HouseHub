<?php

namespace househub\groups\home;

use househub\access\DatabaseConnector;
use househub\groups\dao\GroupElementDAO;
use househub\groups\dao\GroupStructureDAO;
use househub\groups\dao\GroupVisualDAO;
use househub\groups\GroupElement;
use househub\groups\tables\GroupElementsTable;
use househub\groups\tables\GroupVisualTable;
use househub\users\rights\UserViews;
use lightninghowl\utils\sql\SqlCriteria;
use lightninghowl\utils\sql\SqlExpression;
use lightninghowl\utils\sql\SqlFilter;
use PDO;

class HomeGroupManager {

    public function loadGroup($identifier, $userId, PDO $driver) {
        $group = new HomeGroup();
        $structure = $this->loadStructure($identifier, $driver);
        $group->setStructure($structure);

        if (is_null($group->getStructure())) {
            return null;
        }
        $group->setVisual($this->loadVisual($identifier, $userId, $driver));
        $group->setObjects($this->loadObjects($identifier, $userId, $driver));

        return $group;
    }

    public function loadStructure($identifier, PDO $driver) {
        if (!is_numeric($identifier)) {
            return null;
        }

        $structureDAO = new GroupStructureDAO($driver);
        $structure = $structureDAO->load($identifier);
        return $structure;
    }

    public function loadVisual($identifier, $userId, PDO $driver) {
        if (!is_numeric($identifier) || !is_numeric($userId)) {
            return null;
        }

        $visual = null;
        $visualDAO = new GroupVisualDAO($driver);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(GroupVisualTable::COLUMN_GROUP_ID, '=', $identifier), SqlExpression::AND_OPERATOR);

        $subCriteria = new SqlCriteria();
        $subCriteria->add(new SqlFilter(GroupVisualTable::COLUMN_USER_ID, '=', 0), SqlExpression::OR_OPERATOR);
        $subCriteria->add(new SqlFilter(GroupVisualTable::COLUMN_USER_ID, '=', $userId), SqlExpression::OR_OPERATOR);

        $criteria->add($subCriteria);
        $visuals = $visualDAO->listAll($criteria);

        foreach ($visuals as $singleVisual) {
            if ($visual == null) {
                $visual = $singleVisual;
            } else if ($visual->getUserId() == 0 && $singleVisual->getUserId() > 0) {
                $visual = $singleVisual;
            }
        }

        return $visual;
    }

    public function loadObjects($identifier, $userId, PDO $driver) {
        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(GroupElementsTable::COLUMN_GROUP_ID, '=', $identifier));
        $groupElementDAO = new GroupElementDAO($driver);
        $elements = $groupElementDAO->listAll($criteria);

        return $elements;
    }

    public function saveGroup(HomeGroup $homeGroup, PDO $driver) {
        if (is_null($homeGroup) || is_null($homeGroup->getStructure())) {
            return null;
        }

        $savedStructure = $this->saveGroupStructure($homeGroup->getStructure(), $driver);
        $homeGroup->setStructure($savedStructure);
        
        $groupId = $savedStructure->getId();
        
        if(!is_null($homeGroup->getVisual())){
            $savedVisual = $this->saveGroupVisual($groupId,$homeGroup->getVisual(),$driver);
            $homeGroup->setVisual($savedVisual);
        }
        
        
        if(!is_null($homeGroup->getObjects())){
            $savedElements = $this->saveGroupElements($groupId, 
                                                      $homeGroup->getUserId(), 
                                                      $homeGroup->getObjects(), $driver);
            $homeGroup->setObjects($savedElements);
        }
        
        return $homeGroup;
    }
    
    public function saveGroupStructure($structure, $driver){
        $groupDAO = new GroupStructureDAO($driver);
        
        if($groupDAO->update($structure) <= 0){
            $insertedId = $groupDAO->insert($structure);
            if(!is_numeric($insertedId) || $insertedId <= 0){
                return null;
            }
            $structure->setId($insertedId);
        }
        
        return $structure;
    }

    public function saveGroupVisual($groupId,$groupVisual, $driver){
        $groupVisualDAO = new GroupVisualDAO($driver);
        $groupVisual->setGroupId($groupId);
        if($groupVisualDAO->update($groupVisual) <= 0){
            $insertedId = $groupVisualDAO->insert($groupVisual);
            if(!is_numeric($insertedId) || $insertedId <= 0){
                return null;
            }
            $groupVisual->setId($insertedId);
        }
        
        return $groupVisual;
    }
    
    public function saveGroupElements($groupId, $userId, $groupElements, $driver){
        $permissions = new UserViews($userId, $driver);
        $elementDAO = new GroupElementDAO($driver);
        
        foreach ($groupElements as $key=>$element) {
            if ($permissions->verifyRights($objectId)) {
                $element->setGroupId($groupId);
                $element->setObjectId($objectId);

                $insertedElement = $$this->saveElement($element, $elementDAO);
                $groupElements[$key] = $insertedElement;
            }
        }
        
        return $groupElements;
    }

    public function saveElement($element, $driver) {
        if(($driver instanceof PDO)){
            $elementDAO = new GroupElementDAO($driver);
        }
        else if(($driver instanceof GroupElementDAO)){
            $elementDAO = $driver;
        }
        if($elementDAO->update($element) <= 0){
            $insertedId = $elementDAO->insert($element);
            if(!is_numeric($insertedId) || $insertedId <= 0){
                return null;
            }
            $element->setId($insertedId);
        }
        return $element;
    }
    
    

    public function deleteGroup(HomeGroup $group) {
        $driver = DatabaseConnector::getDriver();
        $structureDAO = new GroupStructureDAO($driver);
        $structureDAO->delete($group->getStructure());

        $visualDAO = new GroupVisualDAO($driver);
        if (!is_null($group->getVisual())) {
            $visualDAO->delete($group->getVisual());
        }

        $objects = $group->getObjects();
        $groupDAO = new GroupElementDAO($driver);
        foreach ($objects as $object) {
            $groupDAO->delete($object);
        }
    }
}

?>