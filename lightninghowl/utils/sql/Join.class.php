<?php

namespace lightninghowl\utils\sql;

/**
 * Represents a SQL "Join" statement
 * 
 * @abstract
 * @author Alison Bento "Lykaios"
 * @version 1.0.1
 */
abstract class Join extends SqlExpression{
	protected $entity;
	protected $criteria;
	
	final public function setEntity($entity){
		$this->entity = $entity;
	}
	
	final public function getEntity(){
		return $this->entity;
	}
	
	final public function setCriteria($criteria){
		$this->criteria = $criteria;
	}
	
	final public function getCriteria(){
		return $this->criteria;
	}
	
	//abstract public function dump();
} 

?>