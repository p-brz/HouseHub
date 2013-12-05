<?php

namespace tests\househub\access\strategies;

use househub\objects\home\HomeObject;
use househub\objects\home\HomeObjectManager;
use househub\objects\ObjectStructure;

/**
 * Description of ObjectMakerHelper
 *
 * @author leobrizolara
 */
class ObjectMakerHelper {
    
    public static function insertNewObject($pdo, $object=null){
        $homeObject = (!is_null($object) ? $object : self::makeObject());
        $homeManager = new HomeObjectManager();
        $homeManager->saveObject($homeObject, $pdo);
        return $homeObject;
    }
    
    public static function makeObject(){
        $homeObj = new HomeObject();
        $homeObj->setStructure(self::makeStructure());
        return $homeObj;
    }
    
    public static function makeManyObjects(){
        $objects = array();
        $objects[] = self::makeObject();
        $objects[] = self::makeObject();
        $objects[] = self::makeObject();
        
        return $objects;
    }
    
    /**
     * @return ObjectStructure
     */
    private static function makeStructure(){
        $structure = new ObjectStructure();
        $structure->setAddress("http://192.168.0.100");
        $structure->setSchemeName("basicDoor");
        $structure->setConnected(false);
        $structure->setType("door");
        
        return $structure;
    }
}
