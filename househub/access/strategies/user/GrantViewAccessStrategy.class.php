<?php

// Complete

namespace househub\Access\Strategies\User;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\users\session\SessionManager;
use LightningHowl\Utils\Sql\InsertQuery;

class GrantViewAccessStrategy extends AbstractAccessStrategy {

    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();


        $driver = DatabaseConnector::getDriver();

        $sessManager = SessionManager::getInstance();
        $userId = $sessManager->getSessionVariable('user_id');
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
        } else if ($userId != 0) {
            $answer->setMessage('@forbidden');
        } else if (!isset($parameters['user']) || !isset($parameters['objects'])) {
            $answer->setMessage('@bad_parameters');
        } else if (!is_int($parameters['user']) || !is_array($parameters['objects'])) {
            $answer->setMessage('@bad_types');
        } else {
            $grantUserId = $parameters['user'];
            $noError = true;
            $queries = array();
            foreach ($parameters['objects'] as $objectId) {
                if (!is_int($objectId)) {
                    $answer->setMessage('@bad_types');
                    $noError = false;
                    break;
                } else {
                    $insertQuery = new InsertQuery();
                    $insertQuery->setEntity('uhb_user_views');
                    $insertQuery->setRowData('user_id', $grantUserId);
                    $insertQuery->setRowData('object_id', $objectId);

                    $queries[] = $insertQuery;
                }
            }

            if ($noError) {
                foreach ($queries as $query) {
                    $driver->exec($query->getInstruction());
                }

                $answer->setStatus(1);
                $answer->setMessage('@success');
            }
        }

        return $answer;
    }

}

?>