<?php

namespace househub\access\strategies\groups;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\groups\home\HomeGroupJsonParser;
use househub\groups\home\HomeGroupManager;
use househub\json\JsonArray;
use househub\users\rights\UserGroups;
use househub\users\session\SessionManager;

class ListGroupsAccessStrategy extends AbstractAccessStrategy {

    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();

        $sessMan = SessionManager::getInstance();
        $userId = $sessMan->getSessionVariable('user_id');
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
        } else {
            $this->listGroups($userId, $answer);
        }

        return $answer;
    }

    protected function listGroups($userId, $answer) {
        $driver = DatabaseConnector::getDriver();
        
        $permissions = new UserGroups($userId, $driver);
        $groups = $permissions->getRights();

        $json = new JsonArray();

        $manager = new HomeGroupManager();
        $parser = new HomeGroupJsonParser();
        foreach ($groups as $group) {
            $homeGroup = $manager->loadGroup($group, $userId, $driver);
            $jsonGroup = $parser->parse($homeGroup);

            $json->addElement($jsonGroup);
        }

        $answer->setStatus(1);
        $answer->setMessage('@success');
        $answer->setContent($json);
    }

}

?>