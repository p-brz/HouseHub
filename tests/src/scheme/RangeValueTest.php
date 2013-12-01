<?php

namespace househub\tests\scheme;

require_once __DIR__.'/../../scheme/AllowedValue.class.php';
require_once __DIR__.'/../../scheme/RangeValue.class.php';

use househub\scheme\RangeValue;

class RangeValueTest extends \PHPUnit_Framework_TestCase{
	public function testIsValidOk(){
        $rangeValue = new RangeValue(0,100);
        
		$this->assertTrue($rangeValue->isValid(50));
	}	
}

?>
