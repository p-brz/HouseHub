<?php

namespace househub\access\strategies\object;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\objects\dao\ObjectVisualIconpackDAO;
use househub\objects\dao\ObjectVisualNameDAO;
use househub\objects\home\HomeObjectManager;
use househub\objects\ObjectVisualName;
use househub\users\rights\UserViews;
use househub\users\session\SessionManager;

class SetViewAccessStrategy extends AbstractAccessStrategy {

    const USER_ARG = 'user_id';
    const OBJECT_ARG = 'object';
    const OBJNAME_ARG = 'name';
    const ICONPACK_ARG = 'iconpack';

    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();
        $driver = DatabaseConnector::getDriver();

        if ($this->checkValidRequest($parameters, $answer, $driver)) {
            $this->setView($parameters, $answer, $driver);
        }

        return $answer;
    }

    protected function checkValidRequest($parameters, &$answer, $driver) {
        return $this->checkParameters($parameters, $answer, $driver) && $this->checkPermission($parameters, $answer, $driver);
    }

    protected function checkParameters($parameters, &$answer, $driver) {
        if (!isset($parameters[self::OBJECT_ARG]) || 
                (!isset($parameters[self::OBJNAME_ARG]) && !isset($parameters[self::ICONPACK_ARG]))) 
        {
            $answer->setMessage('@bad_parameters');
            return false;
        }
        return true;
    }

    protected function checkPermission($parameters, &$answer, $driver) {
        $sessManager = SessionManager::getInstance();
        $userId = $sessManager->getSessionVariable(USER_ARG);

        if (!is_null($userId)) {
            $permissions = new UserViews($userId, $driver);
            if (!$permissions->verifyRights(intval($parameters[OBJECT_ARG]))) {
                $answer->setMessage('@forbidden');
                return false;
            }
        } else {
            $answer->setMessage('@user_needs_login');
            return false;
        }
        return true;
    }

    protected function setView($parameters, $answer, $driver) {
        $objectId = intval($parameters[OBJECT_ARG]);
        $manager = new HomeObjectManager();
        if (isset($parameters[OBJNAME_ARG])) {
            $name = urldecode($parameters[OBJNAME_ARG]);
            $objectName = $manager->loadVisualName($objectId, $userId, $driver);
            $objectNameDAO = new ObjectVisualNameDAO($driver);
            if (!is_null($objectName)) {
                $objectName->setObjectName($name);
                $objectNameDAO->update($objectName);
            } else {
                $objectName = new ObjectVisualName();
                $objectName->setUserId($userId);
                $objectName->setObjectId($objectId);
                $objectName->setObjectName($name);

                $nameId = $objectNameDAO->insert($objectName);
                $objectName->setId($nameId);
            }
        }

        if (isset($parameters[ICONPACK_ARG])) {
            $iconpackId = $parameters[ICONPACK_ARG];
            $objectIconpack = $manager->loadVisualIconpack($objectId, $userId, $driver);
            $objectIconpackDAO = new ObjectVisualIconpackDAO($driver);
            if (!is_null($objectIconpack)) {
                $objectIconpack->setIconpackId($iconpackId);
                $objectIconpackDAO->update($objectIconpack);
            } else {
                $objectIconpack = new ObjectVisualIconpack();
                $objectIconpack->setUserId($userId);
                $objectIconpack->setObjectId($objectId);
                $objectIconpack->setIconpackId($iconpackId);

                $nameId = $objectIconpackDAO->insert($objectIconpack);
                $objectIconpack->setId($nameId);
            }
        }

        $answer->setStatus(1);
        $answer->setMessage('@success');
    }

}

?>