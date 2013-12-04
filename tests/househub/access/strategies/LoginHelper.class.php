<?php

namespace tests\househub\access\strategies;
//
//$d = DIRECTORY_SEPARATOR;
//require_once (__DIR__ . $d . "session". $d . "LoginAccessStrategy.class.php");
//require_once (__DIR__ . $d."session". $d . "LoginAccessStrategy.class.php");

use househub\access\strategies\session\LoginAccessStrategy;
use househub\access\strategies\session\VerifyLoginAccessStrategy;

/**
 * Description of doLogin
 *
 * @author leobrizolara
 */
class LoginHelper {
    private $loginStrategy;
    private $verifyLoginStrategy;
    private $sessId;
    
    public function __construct() {
        $this->loginStrategy = new LoginAccessStrategy();
        $this->verifyLoginStrategy = new VerifyLoginAccessStrategy();
    }
    
    public function isLogged(){
        $answer = $this->verifyLoginStrategy->requestAccess(array(
            "method" => "verify_login"
        ));
        return ($answer->getStatus() == 1);
    }
    
    public function doLogin($login="adm",$pass ="123456") {
        $parameters = array(
            "method" => "login",
            "username" => $login,
            "password" => $pass
        );
        $answer = $this->loginStrategy->requestAccess($parameters); 
        if($answer->getStatus() == 1){
            $this->sessId = $answer->getContent()->getElement("phpsessid");
        }
        
        return $answer->getStatus() == 1;
    }
    
    public function doLogoff(){
        $_SESSION['user_id'] = null;//gambiarra!?
    }


    public function getSessId() {
        return $this->sessId;
    }


}
