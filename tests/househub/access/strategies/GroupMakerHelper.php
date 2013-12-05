<?php

$d = DIRECTORY_SEPARATOR;
require_once(__DIR__ . $d . "ObjectMakerHelper.class.php");

use househub\groups\GroupStructure;
use househub\groups\home\HomeGroup;
use househub\groups\home\HomeGroupManager;
use tests\househub\access\strategies\ObjectMakerHelper;

/**
 * Description of GroupMakerHelper
 *
 * @author leobrizolara
 */
class GroupMakerHelper {
    public static function insertNewGroup($pdo){
        $groupManager = new HomeGroupManager();
        $groupManager->saveGroup();
    }
    public static function makeHomeGroup() {
        $group = new HomeGroup();
        $group->setStructure(self::makeGroupStructure());
        $group->setObjects(ObjectMakerHelper::makeManyObjects());
        $group->setVisual(null);
    }
    public static function makeGroupStructure() {
        $groupStructure = new GroupStructure();
        $groupStructure->setUserId(0);
        return $groupStructure;
    }
    
//    public static function makeGroupVisual(){
//        $visual = new GroupVisual();
//        $visual->
//    }
    
}
