<?php

namespace househub\scheme;

class RangeValue implements AllowedValue{
	private $min;
	private $max;
	
	public function __construct($minValue, $maxValue){
		$this->min = $minValue;
		$this->max = $maxValue;
	}
	
	public function isValid($value){
//		return ($value >= $this->min || $value <= $this->max);
		return ($value >= $this->min && $value <= $this->max);
	}
	
}

?>
