<?php

namespace lightninghowl\utils\sql;

class SqlCriteria extends SqlExpression{
	private $expressions;
	private $operators;
	private $properties;
	
	public function __construct(){
		$this->expressions = array();
		$this->operators = array();
	}
	
	public function add(SqlExpression $expression, $operator = self::AND_OPERATOR){
		if(empty($this->expressions)){
			$operator = NULL;
		}
		
		$this->expressions[] = $expression;
		$this->operators[] = $operator;
	}
	
	public function dump(){
		if(is_array($this->expressions)){
			if(count($this->expressions) > 0){
				$result = '';
				foreach($this->expressions as $key=>$expression){
					$operator = $this->operators[$key];
					$result .= $operator.$expression->dump().' ';
				}
				$result = trim($result);
				return "({$result})";
			}
		}
	}
	
	public function setBlockStatus($property, $value){
		if(isset($value)){
			$this->properties[$property] = $value;
		}
		else{
			$this->properties[$property] = NULL;
		}
	}
	
	public function getProperty($property){
		if(isset($this->properties[$property])){
			return $this->properties[$property];
		}
	}
}

?>