<?php

namespace lightninghowl\database;

use lightninghowl\database\arguments\DbArgument;
use PDO;

/**
 * Static class, used to connect to the database
 * 
 * Por meio desta classe, obtemos uma conexão com o banco de dados
 * baseado no objeto PDO padrão do PHP.
 * 
 * @author Alison Bento "Lykaios"
 * @version 1.0.0
 * @static
 */
final class DbDriver{
	private static $connection;
	
	private function __construct(){}
	
	/**
	 * Open the database connection
	 * 
	 * @static
	 * @param DbArgument $argument The argument to open the connection
	 */
	public static function open(DbArgument $argument){
		$conn = new PDO($argument->getDSN(), $argument->getLogin(), $argument->getPassword());
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
}

?>