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
    }
    
    public function doLogin() {
        $parameters = array(
            "method" => "login",
            "username" => "adm",
            "password" => "123456"
        );
        $answer = $this->loginStrategy->requestAccess($parameters); 
        if($answer->getStatus() == 1){
            $this->sessId = $answer->getContent()->getElement("phpsessid");
        }
    }
    
    public function getSessId() {
        return $this->sessId;
    }


}
