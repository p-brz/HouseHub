<?php

namespace househub\access\strategies\object;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\json\JsonArray;
use househub\objects\home\HomeObjectJsonParser;
use househub\objects\home\HomeObjectManager;
use househub\users\rights\UserViews;
use househub\users\session\SessionManager;

class ListObjectsAccessStrategy extends AbstractAccessStrategy {

    private $dbDriver;
    
    public function __construct($driver = null){
        $this->dbDriver = (!is_null($driver)? $driver : DatabaseConnector::getDriver());
    }
    
    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();
        $driver = DatabaseConnector::getDriver();

        $sessMan = SessionManager::getInstance();
        $userId = $sessMan->getSessionVariable('user_id');
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
        } else {
            $this->listObjects($userId, $answer, $driver);
        }

        return $answer;
    }

    protected  function listObjects($userId, $answer, $driver){
        $json = new JsonArray();
        $permissions = new UserViews($userId, $driver);
        $objects = $permissions->getRights();

        $parser = new HomeObjectJsonParser();
        $manager = new HomeObjectManager();
        foreach ($objects as $object) {
            $homeObject = $manager->loadObject($object, $userId, $driver);
            $jsonObject = $parser->parse($homeObject);

            $json->addElement($jsonObject);
        }

        $answer->setStatus(1);
        $answer->setMessage('@success');
        $answer->setContent($json);
    }
    
}

?>