<?php

namespace Core\Access\Strategies\User;

use LightningHowl\Utils\Sql\InsertQuery;

use LightningHowl\Utils\Encrypting\Sha1Hash;

use LightningHowl\Utils\Sql\UpdateQuery;

use LightningHowl\Utils\Sql\SqlFilter;

use LightningHowl\Utils\Sql\SqlCriteria;

use LightningHowl\Utils\Sql\SelectQuery;

use Core\Access\DatabaseConnector;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

use PDO;

class RegisterAdminAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		$driver = DatabaseConnector::getDriver();
		
		$usersQuery = new SelectQuery();
		$usersQuery->addColumn('id');
		$usersQuery->setEntity('users');

		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter('id', '=', 0));
		$usersQuery->setCriteria($criteria);
		
		$statement = $driver->query($usersQuery->getInstruction());
		if($statement->rowCount() > 0){
			$answer->setMessage('@admin_exists');
		}else{
			$hasErrors = false;
			
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
				$insertQuery->setEntity('users');
				$insertQuery->setRowData('name', $name);
				$insertQuery->setRowData('nickname', $nickname);
				$insertQuery->setRowData('gender', $gender);
				$insertQuery->setRowData('login', $login);
				$insertQuery->setRowData('password', $pass);
				
				$driver->exec($insertQuery->getInstruction());
				$id = $driver->lastInsertId();
				
				$updateQuery = new UpdateQuery();
				$updateQuery->setEntity('users');
				$updateQuery->setRowData('id', 0);
				
				$criteria = new SqlCriteria();
				$criteria->add(new SqlFilter('id', '=', $id));
				$updateQuery->setCriteria($criteria);
				
				$driver->exec($updateQuery->getInstruction());
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		return $answer;
		
	}
	
}

?>