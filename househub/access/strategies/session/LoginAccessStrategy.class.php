<?php

namespace househub\access\strategies\session;

/**
 * Authenticates an user
 * 
 * Utilizado para autenticar e reservar sessões para usuários
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 */

use househub\json\JsonData;

use househub\access\strategies\AbstractAccessStrategy;

use househub\users\session\SessionManager;

use househub\users\authentication\PasswordAuthentication;

use househub\access\DatabaseConnector;

use househub\answer\AnswerEntity;

class LoginAccessStrategy extends AbstractAccessStrategy{
	const USER_ARG = "username";
	const PASS_ARG = "password";
        
	public function requestAccess($parameters){
            $answer = new AnswerEntity();
            $answer->setStatus(0);
            $answer->setMessage('@error');

            $driver = DatabaseConnector::getDriver();

            if(!isset($parameters[self::USER_ARG])){
                $answer->setMessage('@login_null_username');

            }else if(!isset($parameters[self::PASS_ARG])){
                $answer->setMessage('@login_null_password');
            }else{
                $user = $this->authenticateUser($parameters, $driver);
                $this->makeSession($user,$answer);
            }

            return $answer;
		
	}
        
        protected function makeSession($user,&$answer){
            if($user){
                $sessManager = SessionManager::getInstance();
                $sessManager->startSession();
                $sessManager->setSessionVariable("user_id", $user->getId());

                $answer->setStatus(1);
                $answer->setMessage('@success');

                $userRights = $user->getId() > 0 ? 1 : 0;

                $answer->addContent(new JsonData("phpsessid", session_id()));
                $answer->addContent(new JsonData("user_rights", $userRights));
            }else{
                $answer->setMessage('@login_wrong');
            }
        }

        protected function authenticateUser($parameters, $driver){
            $items = array('user' => $parameters[self::USER_ARG], 
                               'password' => $parameters[self::PASS_ARG]);

            $auth = new PasswordAuthentication();
            $auth->setParameters($items);

            return $auth->authenticate($driver);
        }
	
}

?>