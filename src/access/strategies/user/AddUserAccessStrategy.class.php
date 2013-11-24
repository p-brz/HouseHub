<?php
// Complete
namespace househub\access\strategies\user;

use househub\users\UserStructure;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use househub\access\strategies\AbstractAccessStrategy;

use househub\users\tables\UserStructureTable;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\encrypting\Sha1Hash;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use PDO;

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
			
			$name = '';
			$nickname = '';
			$gender = '';
			$login = '';
			$password = '';
			
			if(!isset($parameters['name'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['name']) > 100){
				$hasErrors = true;
								
			}else{
				$name = urldecode($parameters['name']);
			}
			
			if(!isset($parameters['nickname'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['nickname']) > 20){
				$hasErrors = true;
								
			}else{
				$nickname = urldecode($parameters['nickname']);
			}
			
			if(!isset($parameters['gender'])){
				$hasErrors = true;
				
			}else{
				$gender = urldecode($parameters['gender']);
			}
			
			if(!isset($parameters['login'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['login']) > 25){
				$hasErrors = true;
								
			}else{
				$login = strtolower(urldecode($parameters['login']));
			}
			
			if(!isset($parameters['password'])){
				$hasErrors = true;
				
			}else if(strlen($parameters['password']) > 12){
				$hasErrors = true;
								
			}else{
				$password = urldecode($parameters['password']);
			}
			
			if($hasErrors){
				$answer->setMessage('@error');
			}else{
				
				$select = new SelectQuery();
				$select->addColumn(UserStructureTable::COLUMN_ID);
				$select->setEntity(UserStructureTable::TABLE_NAME);
				
				$criteria = new SqlCriteria();
				$criteria->add(new SqlFilter('LCASE('.UserStructureTable::COLUMN_USERNAME.')', 'like', $login));
				$select->setCriteria($criteria);
				
				$statement = $driver->query($select->getInstruction());
				
				if($statement->rowCount() > 0){
					$answer->setMessage('@login_already_taken');
				}else{
					$encoded = new Sha1Hash();
					$pass = $encoded->encrypt($password);
					$insertQuery = new InsertQuery();
					$insertQuery->setEntity(UserStructureTable::TABLE_NAME);
					$insertQuery->setRowData(UserStructureTable::COLUMN_NAME, $name);
					$insertQuery->setRowData(UserStructureTable::COLUMN_NICKNAME, $nickname);
					$insertQuery->setRowData(UserStructureTable::COLUMN_GENDER, $gender);
					$insertQuery->setRowData(UserStructureTable::COLUMN_USERNAME, $login);
					$insertQuery->setRowData(UserStructureTable::COLUMN_PASSWORD, $pass);
	
					$driver->exec($insertQuery->getInstruction());
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
				}
			}
		}
		
		return $answer;
		
	}
	
}

?>