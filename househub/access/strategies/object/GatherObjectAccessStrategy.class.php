<?php

namespace househub\access\strategies\object;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\objects\home\HomeObjectJsonParser;
use househub\objects\home\HomeObjectManager;
use househub\users\rights\UserViews;
use househub\users\session\SessionManager;

/**
 * Acquires informations about objects
 * 
 * Seleciona um objeto e então o retorna como json
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 *
 */
class GatherObjectAccessStrategy extends AbstractAccessStrategy {
    const OBJECT_ARG = 'object';
    
    /**
     * @var PDO
     */
    private $dbDriver;
    
    public function __construct(PDO $driver = null) {
        if(is_null($driver)){
            $this->dbDriver = DatabaseConnector::getDriver();
        }
    }
    
    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();

        if($this->checkParameters($parameters, $answer)){
            $userId = $this->getUserId();
            
            if($this->checkPermission($parameters,$userId,$answer)){
                $this->gatherObject($parameters, $userId, $answer);
            }
        }
        
        return $answer;
    }

    protected function checkParameters($parameters, $answer){
        if (!isset($parameters[self::OBJECT_ARG])) {
            $answer->setMessage("@gather_object_error_1");
            return false;
        }
        return true;
    }
    
    protected function getUserId(){
        $sessManager = SessionManager::getInstance();
        $sessManager->startSession();
        $userId = $sessManager->getSessionVariable('user_id');
        return $userId;
    }
    protected function checkPermission($parameters, $userId, $answer){
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
            return false;
        } 
        else{
            $permission = new UserViews($userId, $this->dbDriver);
            if (!$permission->verifyRights($parameters[self::OBJECT_ARG])) {
                $answer->setMessage('@forbidden');
                return false;
            } 
        }
        return true;
    }


    protected function gatherObject($parameters, $userId, &$answer) {
        $objectId = $parameters[self::OBJECT_ARG];

        $loader = new HomeObjectManager();
        $object = $loader->loadObject($objectId, $userId, $this->dbDriver);
        if (!is_null($object)) {
            $objectParser = new HomeObjectJsonParser();
            $objectJson = $objectParser->parse($object, true);

            $answer->setStatus(1);
            $answer->setMessage('@success');
            $answer->setContent($objectJson);
        } else {
            $answer->setMessage('@gather_object_error_2');
        }
    }

}

?>