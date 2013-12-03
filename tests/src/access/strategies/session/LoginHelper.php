<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace househub\access\strategies\session;
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
