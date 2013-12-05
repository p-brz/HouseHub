<?php

namespace tests\househub\access\strategies;

use househub\groups\dao\GroupElementDAO;
use househub\groups\dao\GroupStructureDAO;
use househub\groups\dao\GroupVisualDAO;
use househub\groups\GroupElement;
use househub\groups\GroupStructure;
use househub\groups\GroupVisual;
use househub\groups\home\HomeGroup;

$d = DIRECTORY_SEPARATOR;
require_once(__DIR__ . $d . "ObjectMakerHelper.class.php");

/**
 * Description of GroupMakerHelper
 *
 * @author leobrizolara
 */
class GroupMakerHelper {
    public static function makeHomeGroup() {
        $group = new HomeGroup();
        $group->setStructure(self::makeGroupStructure());
//        $group->setObjects(self::);
        $group->setVisual(self::makeGroupVisual());
    }
    public static function insertNewHomeGroup($driver, $userId=0) {
        $group = new HomeGroup();
        $group->setStructure(self::insertNewGroupStructure($userId, $driver));
        $objects = ObjectMakerHelper::makeManyObjects();
        
        $objIds = array();
        foreach($objects as $obj){
            if(!is_null($obj->getVisualName())){
                    $obj->getVisualName()->setUserId($userId);
            }
            else{
                $newVisual = new \househub\objects\ObjectVisualName();
                $newVisual->setUserId($userId);
                        
                $obj->setVisualName($newVisual);
            }
            ObjectMakerHelper::insertNewObject($driver, $obj);
            $objIds[] = $obj->getId();
        }
        $group->setObjects(self::insertGroupElements($driver
                , self::makeGroupElements($objIds, $userId, $group->getStructure()->getId())));
        $group->setVisual(self::makeGroupVisual());
        
        return $group;
    }
    
    public static function makeGroupStructure($userId=0) {
        $groupStructure = new GroupStructure();
        $groupStructure->setUserId($userId);
        return $groupStructure;
    }
    
    public static function  insertNewGroupStructure($userId, $driver){
        $groupDAO = new GroupStructureDAO($driver);
        $group = new GroupStructure();
        $group->setUserId($userId);
        $groupId = $groupDAO->insert($group);
        $group->setId($groupId);
        return $group;
    }
    
    public static function makeGroupVisual($groupName="groupName",$userId = 0, $groupId = 0, $groupImage = 0){
        $groupVisual = new GroupVisual();
        $groupVisual->setUserId($userId);
        $groupVisual->setGroupId($groupId);
        
        $groupVisual->setGroupName($groupName);

        $groupVisual->setGroupImageId($groupImage);
        
        return $groupVisual;
    }


    protected function insertGroupVisual($userId, $groupId,$driver, $groupVisual=null){
        if(is_null($groupVisual)){
            $groupVisual = self::makeGroupVisual();
        }
        $groupVisual->setUserId($userId);
        $groupVisual->setGroupId($groupId);
        
        $groupVisualDAO = new GroupVisualDAO($driver);
        $id = $groupVisualDAO->insert($groupVisual);
        $groupVisual->setId($id);
        
        return $groupVisual;
    }
    
    
    public static function makeGroupElement($objectId, $groupId){
//        $elementDAO = new GroupElementDAO($driver);

        $element = new GroupElement();
        $element->setGroupId($groupId);
        $element->setObjectId($objectId);
        
        return $element;
    }
    public static function makeGroupElements($objects, $userId, $groupId){
        $elements = array();
        foreach ($objects as $objectId) {
            $elements[] = self::makeGroupElement($objectId, $groupId);
        }
        
        return $elements;
    }
    
    public static function insertGroupElement($driver, $element){
        $dao = new GroupElementDAO($driver);
        $key = $dao->insert($element);
        $element->setId($key);
        
        return $element;
    }
    public static function insertGroupElements($driver, $elements){
        $dao = new GroupElementDAO($driver);
        foreach($elements as $key=>$element){
            $elements[$key] = self::insertGroupElement($driver, $element);
        }
        return $elements;
    }
    
    
    
//    public static function makeGroupVisual(){
//        $visual = new GroupVisual();
//        $visual->
//    }
    
}
