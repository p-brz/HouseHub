<?php

namespace lightninghowl\database\arguments;

/**
 * Used to set up the database connection, defining the DNS, Login and Password to the PDO object
 * 
 * @author Alison Bento "Lykaios"
 * @version 1.0.0
 * 
 */
interface DbArgument{
	/**
	 * Returns the PDO's first parameter, DSN
	 */
	public function getDSN();
	
	/**
	 * Returns the PDO's second parameter, Login information
	 */
	public function getLogin();
	
	/**
	 * Returns the PDO's last parameter, Password information
	 */
	public function getPassword();
}

?>