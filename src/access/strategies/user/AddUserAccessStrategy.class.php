<?php
// Complete
namespace househub\access\strategies\user;

use househub\users\UserStructure;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use househub\access\strategies\AbstractAccessStrategy;

use househub\users\tables\UserStructureTable;

use househub\users\session\SessionManager;
use househub\users\dao\UserStructureDAO;

use househub\access\DatabaseConnector;

class AddUserAccessStrategy extends AbstractAccessStrategy{
    const NAME_ARG   = "name";
    const NICK_ARG   = "nickname";
    const GENDER_ARG = "gender";
    const LOGIN_ARG  = "login";
    const PASS_ARG   = "password";
    
    private $dbDriver;
    
    public function __construct($driver = null) {
        $this->dbDriver = (is_null($driver)? DatabaseConnector::getDriver() : $driver);
    }


    public function requestAccess($parameters){
        $answer = $this->initializeAnswer();
        
        if($this->checkSession()){
            if($this->checkParameters($parameters)){
                $this->createUser($parameters, $answer);
            }
            else{
                $answer->setMessage('@error');
            }
        }else{
            $answer->setMessage('@forbidden');
        }

        return $answer;
    }
    
    protected function checkSession(){
        $sessManager = SessionManager::getInstance();
        $userId = $sessManager->getSessionVariable('user_id');
        return (!is_null($userId) && is_numeric($userId) && $userId >= 0);
    }
    
    protected function checkParameters(&$parameters){        
        if($this->checkName($parameters)
                && $this->checkNickname($parameters)
                && $this->checkGender($parameters)
                && $this->checkLogin($parameters)
                && $this->checkPassword($parameters))
        {
            
            $parameters[self::NAME_ARG]  = urldecode($parameters[self::NAME_ARG]);
            $parameters[self::NICK_ARG]  = urldecode($parameters[self::NICK_ARG]);
            $parameters[self::GENDER_ARG]= urldecode($parameters[self::GENDER_ARG]);
            $parameters[self::LOGIN_ARG] = strtolower(urldecode($parameters[self::LOGIN_ARG]));
            $parameters[self::PASS_ARG]  = urldecode($parameters[self::PASS_ARG]);
            
            return true;
        }
        return false;
    }
    public function checkName($parameters){
        return isset($parameters[self::NAME_ARG]) && (strlen($parameters[self::NAME_ARG]) <= 100);
    }
    public function checkNickname($parameters){
        return isset($parameters[self::NICK_ARG]) && (strlen($parameters[self::NICK_ARG]) <= 20);
    }
    public function checkGender($parameters){
        return isset($parameters[self::GENDER_ARG]);
    }
    public function checkLogin($parameters){
        return isset($parameters[self::LOGIN_ARG]) && (strlen($parameters[self::LOGIN_ARG]) <= 25);
    }
    public function checkPassword($parameters){
        return isset($parameters[self::PASS_ARG]) && (strlen($parameters[self::PASS_ARG]) <= 12);
    }
//    protected function createUser($parameters){
//        if(!$this->existUser($parameters[self::LOGIN_ARG])){
//            $insertQuery = $this->makeInsertQuery($parameters);
//            $driver->exec($insertQuery->getInstruction());
//
//            $answer->setStatus(1);
//            $answer->setMessage('@success');
//        }
//        else{
//            $answer->setMessage('@login_already_taken');
//        }
//    }
//    
//    protected function makeInsertQuery($parameters){
//        $insertQuery = new InsertQuery();
//        $insertQuery->setEntity(UserStructureTable::TABLE_NAME);
//        $insertQuery->setRowData(UserStructureTable::COLUMN_NAME    , $parameters[self::NAME_ARG]);
//        $insertQuery->setRowData(UserStructureTable::COLUMN_NICKNAME, $parameters[self::NICK_ARG]);
//        $insertQuery->setRowData(UserStructureTable::COLUMN_GENDER  , $parameters[self::GENDER_ARG]);
//        $insertQuery->setRowData(UserStructureTable::COLUMN_USERNAME, $parameters[self::LOGIN_ARG_ARG]);
//
//        $encoded = new Sha1Hash();
//        $passEncoded = $encoded->encrypt($parameters[self::PASS_ARG]);
//        $insertQuery->setRowData(UserStructureTable::COLUMN_PASSWORD, $passEncoded);
//        
//        return $insertQuery;
//    }
    protected function createUser($parameters,&$answer){
        if(!$this->existUser($parameters[self::LOGIN_ARG])){
            $newUser = $this->makeUser($parameters);
            
            $userDAO = new UserStructureDAO($this->dbDriver);
            $userDAO->insert($newUser);

            $answer->setStatus(1);
            $answer->setMessage('@success');
        }
        else{
            $answer->setMessage('@login_already_taken');
        }
    }
    
    protected function makeUser($parameters){
        $newUser = new UserStructure();
        $newUser->setName($parameters[self::NAME_ARG]);
        $newUser->setNickname($parameters[self::NICK_ARG]);
        $newUser->setGender($parameters[self::GENDER_ARG]);
        $newUser->setUsername($parameters[self::LOGIN_ARG]);
        $newUser->setPassword($parameters[self::PASS_ARG]);        
        return $newUser;
    }
    
    protected function existUser($login){
        $driver = DatabaseConnector::getDriver();
            
        $select = new SelectQuery();
        $select->addColumn(UserStructureTable::COLUMN_ID);
        $select->setEntity(UserStructureTable::TABLE_NAME);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter('LCASE('.UserStructureTable::COLUMN_USERNAME.')', 'like', $login));
        $select->setCriteria($criteria);

        $statement = $driver->query($select->getInstruction());
        
        return ($statement->rowCount() > 0);
    }

}

?>