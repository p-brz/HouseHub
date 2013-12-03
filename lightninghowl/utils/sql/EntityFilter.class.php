<?php

namespace lightninghowl\utils\sql;

class EntityFilter extends SqlFilter{
	public function __construct($variable, $operator, $value){
		parent::__construct($variable, $operator, $value);
	}
	
	public function dump(){
		return "{$this->variable} {$this->operator} {$this->value}";
	}
	
}