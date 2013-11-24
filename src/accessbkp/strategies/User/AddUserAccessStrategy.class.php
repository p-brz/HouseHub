<?php
namespace househub\access\strategies;

use househub\access\DatabaseConnector;

class AddUserAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId) || $userId != 0){
			$answer->setMessage('@forbidden');
		}else{
			
			$hasErrors = false;
			
			$name;
			$nickname;
			$gender;
			$login;
			$password;
			
			if(!isset($parameters['name'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['name']) > 100){
				$hasErrors = true;
								
			}else{
				$name = $parameters['name'];
			}
			
			if(!isset($parameters['nickname'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['nickname']) > 20){
				$hasErrors = true;
								
			}else{
				$nickname = $parameters['nickname'];
			}
			
			if(!isset($parameters['gender'])){
				$hasErrors = true;
				
			}else{
				$gender = $parameters['gender'];
			}
			
			if(!isset($parameters['login'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['login']) > 25){
				$hasErrors = true;
								
			}else{
				$login = $parameters['login'];
			}
			
			if(!isset($parameters['password'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['password']) > 12){
				$hasErrors = true;
								
			}else{
				$password = $parameters['password'];
			}
			
			if($hasErrors){
				$answer->setMessage('@error');
			}else{
				
				$encoded = new Sha1Hash();
				$pass = $encoded->encrypt($password);
				$insertQuery = new InsertQuery();
				$insertQuery->setEntity('uhb_users');
				$insertQuery->setRowData('name', $name);
				$insertQuery->setRowData('nickname', $nickname);
				$insertQuery->setRowData('gender', $gender);
				$insertQuery->setRowData('login', $login);
				$insertQuery->setRowData('password', $pass);

				$driver->exec($insertQuery->getInstruction());
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		return $answer;
		
	}
	
}

?>