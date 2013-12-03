<?php
namespace lightninghowl\database\arguments;

/**
 * Set up a connection based on a MySQL database
 * 
 * @author Alison Bento "Lykaios"
 * @version 1.0.0
 * 
 */
class MySQLArgument implements DbArgument{
	/**
	 * The database host
	 * @var string
	 */
	private $host;
	
	
	/**
	 * The database port
	 * @var int
	 */
	private $port;
	
	/**
	 * The database name which will be opened
	 * @var string
	 */
	private $dbName;
	
	/**
	 * The username used to access this database
	 * @var string
	 */
	private $dbUser;
	
	/**
	 * The password of the user
	 * @var string
	 */
	private $dbPass;
	
	/**
	 * (non-PHPdoc)
	 * @see uCore/Database/Arguments/lightninghowl\database\Arguments.DbArgument::getLogin()
	 */
	public function getLogin(){
		return $this->dbUser;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see uCore/Database/Arguments/lightninghowl\database\Arguments.DbArgument::getPassword()
	 */
	public function getPassword(){
		return $this->dbPass;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see uCore/Database/Arguments/lightninghowl\database\Arguments.DbArgument::getDSN()
	 */
	public function getDSN(){
		return "mysql:host={$this->host};port={$this->port};dbname={$this->dbName}";
	}
	
	public function getHost() { return $this->host; } 
	public function getPort() { return $this->port; } 
	public function getDbName() { return $this->dbName; } 
	public function getDbUser() { return $this->dbUser; } 
	public function getDbPass() { return $this->dbPass; }
	 
	public function setHost($x) { $this->host = $x; } 
	public function setPort($x) { $this->port = $x; } 
	public function setDbName($x) { $this->dbName = $x; } 
	public function setDbUser($x) { $this->dbUser = $x; } 
	public function setDbPass($x) { $this->dbPass = $x; }
}