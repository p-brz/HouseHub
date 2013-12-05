<?php

namespace tests\househub\access\strategies;

use househub\objects\home\HomeObject;
use househub\objects\home\HomeObjectManager;
use househub\objects\ObjectStructure;
use househub\services\ServiceStructure;

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
    public static function makeStructure(){
        $structure = new ObjectStructure();
        $structure->setAddress("http://192.168.0.100");
        $structure->setSchemeName("basicDoor");
        $structure->setConnected(false);
        $structure->setType("door");
        
        return $structure;
    }
    
    public static function makeServices($objId = 1) {
        $service1 = new ServiceStructure();
        $service1->setName("travar");
        $service1->setObjectId($objId);

        $service2 = new ServiceStructure();
        $service2->setName("destravar");
        $service2->setObjectId($objId);

        return array($service1, $service2);
    }
}
