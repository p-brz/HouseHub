<?php

namespace Core\Access\Strategies\Session;

/**
 * Authenticates an user
 * 
 * Utilizado para autenticar e reservar sessões para usuários
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 */
use Core\Access\Strategies\AbstractAccessStrategy;

use Core\Json\JsonData;

use Core\User\SessionManager;

use Core\Answer\JsonAnswerParser;

use Core\Answer\AnswerEntity;

use Core\Access\DatabaseConnector;

use LightningHowl\Database\DbDriver;

use LightningHowl\Database\Arguments\MySQLArgument;

use LightningHowl\Reader\JsonReader;

use LightningHowl\Utils\StrOpers;

use Core\Reader\SystemReader;

use Core\User\Authentication\PasswordAuthentication;

class LoginAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = new AnswerEntity();
		$answer->setStatus(0);
		$answer->setMessage('@error');
		
		
		
		$driver = DatabaseConnector::getDriver();
		
		if(!isset($parameters['username'])){
			$answer->setMessage('@login_null_username');
			
		}else if(!isset($parameters['password'])){
			$answer->setMessage('@login_null_password');
			
		}else{
			
			$username = $parameters['username'];
			$password = $parameters['password'];
			
			$items = array('user' => $username, 'password' => $password);
			
			$auth = new PasswordAuthentication();
			$auth->setParameters($items);
			
			$user = $auth->authenticate($driver);
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

		return $answer;
		
	}
	
}

?>