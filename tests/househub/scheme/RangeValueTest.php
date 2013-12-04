<?php

namespace househub\scheme;

class RangeValueTest extends \PHPUnit_Framework_TestCase {

    public function testIsValidTrue() {
        $rangeValue = new RangeValue(0, 100);

        $this->assertTrue($rangeValue->isValid(50));
    }

    public function testIsNotValidBelow() {
        $rangeValue = new RangeValue(0, 100);

        $this->assertFalse($rangeValue->isValid(-1));
    }

    public function testIsNotValidAbove() {
        $rangeValue = new RangeValue(0, 100);

        $this->assertFalse($rangeValue->isValid(101));
    }

}

?>
