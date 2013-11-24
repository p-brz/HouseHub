<?php

namespace househub\scheme;

class EnumValue implements AllowedValue{
	private $collection;
	
	public function __construct($rangedArray){
		$this->collection = $rangedArray;
	}
	
	public function isValid($value){
		return in_array($value, $this->collection);
	}
	
}

?>
