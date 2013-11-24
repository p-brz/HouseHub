<?php

namespace lightninghowl\utils\sql;

/**
 * The SQL Instruction entity. All the others queries will implements it's methods
 * @author Alison Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0.1
 */
abstract class SqlInstruction{
	protected $sql;
	protected $criteria;
	protected $entity;
	
	/**
	 * Defines which database entity the query will aim
	 * @param string $entity
	 */
	final public function setEntity($entity){
		$this->entity = $entity;
	}
	
	/**
	 * Returns the query entity
	 * @return string
	 */
	final public function getEntity(){
		return $this->entity;
	}
	
	/**
	 * Set the criteria for the query, such as the WHERE clausule
	 * @param SqlCriteria $criteria
	 */
	public function setCriteria(SqlCriteria $criteria){
		$this->criteria = $criteria;
	}
	
	/**
	 * Converts the query to a string
	 * @abstract
	 * @return string
	 */
	abstract function getInstruction();
}

?>