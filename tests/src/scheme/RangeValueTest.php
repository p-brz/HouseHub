<?php

//namespace househub\tests\scheme;
//
//require_once __DIR__.'/../../scheme/AllowedValue.class.php';
//require_once __DIR__.'/../../scheme/RangeValue.class.php';
//
//use househub\scheme\RangeValue;

namespace househub\scheme;
$d = DIRECTORY_SEPARATOR;
require_once(realpath(__DIR__.$d.'..'.$d.'..'.$d.'..'.$d.'lightninghowl'.$d.'utils'.$d.'AutoLoader.class.php'));


class RangeValueTest extends \PHPUnit_Framework_TestCase{
	public function testIsValidTrue(){
            $rangeValue = new RangeValue(0,100);
        
            $this->assertTrue($rangeValue->isValid(50));
	}	
	public function testIsNotValidBelow(){
            $rangeValue = new RangeValue(0,100);
        
            $this->assertFalse($rangeValue->isValid(-1));
	}	
	public function testIsNotValidAbove(){
            $rangeValue = new RangeValue(0,100);
        
            $this->assertFalse($rangeValue->isValid(101));
	}	
}

?>
