<?php

namespace lightninghowl\utils\sql;

class SqlFilter extends SqlExpression{
	protected $variable;
	protected $operator;
	protected $value;
	
	public function __construct($variable, $operator, $value){
		$this->variable = $variable;
		$this->operator = $operator;
		$this->value = $value;
	}
	
	protected function transform($value){
		if(is_array($value)){
			foreach($value as $item){
				if(is_integer($item)){
					$aux[] = $item;
				}
				else if(is_string($item)){
					$aux[] = "'$item'";
				}
			}
			
			$result = '('.implode(',', $aux).')';
		}
		else if(is_string($value)){
			$result = "'$value'";
		}
		else if(is_null($value)){
			$result = "NULL";
		}
		else if(is_bool($value)){
			$result = $value ? 'TRUE' : 'FALSE';
		}
		else{
			$result = $value;
		}
		
		return $result;
	}
	
	public function dump(){
		return "{$this->variable} {$this->operator} ".$this->transform($this->value);
	}
}