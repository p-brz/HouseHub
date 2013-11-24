<?php

namespace househub\users\authentication;

use lightninghowl\utils\sql\EntityFilter;

use lightninghowl\utils\sql\SqlExpression;

use househub\users\builders\UserStructureBuilder;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\users\tables\UserStructureTable;

use lightninghowl\utils\sql\SelectQuery;

use PDO;

class PasswordAuthentication implements Authentication{
	private $user;
	private $password;
	
	public function setParameters($parameters){
		$this->user = $parameters['user'];
		$this->password = $parameters['password'];
	}
	
	public function authenticate(PDO $driver){
		$user = null;
		
		$query = new SelectQuery();
		$query->addColumn('*');
		$query->setEntity(UserStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(UserStructureTable::COLUMN_USERNAME, '=', $this->user), SqlExpression::AND_OPERATOR);
		$criteria->add(new EntityFilter(UserStructureTable::COLUMN_PASSWORD, '=', "sha1('{$this->password}')"));
		$query->setCriteria($criteria);
		
		$statement = $driver->query($query->getInstruction());
		$builder = new UserStructureBuilder();
		if($statement->rowCount() > 0){
			$rs = $statement->fetch(PDO::FETCH_ASSOC);
			$user = $builder->build($rs);
		}
		
		return $user;
	}
	
}

?>