<?php

namespace househub\conditions;

use househub\status\StatusStructure;

class ObjectConditionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ObjectCondition
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new ObjectCondition("nome");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    /**
     *  househub\conditions\ObjectCondition::setName
     * @todo   Implement testSetName().
     */
    public function testSetName() {
        $this->object->setName('name');
        $this->assertEquals('name', $this->object->getName());
    }

    /**
     *  househub\conditions\ObjectCondition::addRestriction
     * @todo   Implement testAddRestriction().
     */
    public function testAddRestriction() {        
        $this->assertTrue($this->object->isValid(array()));
        $this->object->addRestriction("statusName", "statusValue");
        $this->assertFalse($this->object->isValid(array()));
    }

    /**
     *  househub\conditions\ObjectCondition::getRestriction
     */
    public function testGetRestriction() {
        $this->object->addRestriction("status1", "statusValue");
        $this->object->addRestriction("status3", "anotherValue");
        $restriction = $this->object->getRestriction("status1");
        $this->assertEquals(array("status1","statusValue"), $restriction);
    }
    /**
     *  househub\conditions\ObjectCondition::getRestriction
     */
    public function testGetRestrictionNull() {
        $this->object->addRestriction("status1", "statusValue");
        $this->object->addRestriction("status3", "anotherValue");
        $restriction = $this->object->getRestriction("status2");
        $this->assertNull($restriction);
    }

    /**
     *  househub\conditions\ObjectCondition::removeRestriction
     * @todo   Implement testRemoveRestriction().
     */
    public function testRemoveRestriction() {
        $this->object->addRestriction("statusName", "statusValue");
        $this->assertFalse($this->object->isValid(array()));
        $this->object->removeRestriction("statusName");
        $this->assertTrue($this->object->isValid(array()));
    }

    /**
     *  househub\conditions\ObjectCondition::isValid
     */
    public function testIsValid() {
        $status = array();
        $status[] = new StatusStructure("status1", "statusValue");
        $status[] = new StatusStructure("status2", "otherValue");
        $status[] = new StatusStructure("status3", "anotherValue");
        $this->object->addRestriction("status1", "statusValue");
        $this->object->addRestriction("status3", "anotherValue");
        $this->assertTrue($this->object->isValid($status));
    }
    /**
     *  househub\conditions\ObjectCondition::isValid
     */
    public function testIsValid2() {
        $status = array();
        $status[] = new StatusStructure("status1", "statusValue");
        $status[] = new StatusStructure("status2", "otherValue");
        $status[] = new StatusStructure("status3", "anotherValue");
        $status[] = new StatusStructure("status4", "lastValue");
        $this->object->addRestriction("status3", "anotherValue");
        $this->object->addRestriction("status1", "statusValue");
        $this->object->addRestriction("status4", "lastValue");
        $this->object->addRestriction("status2", "otherValue");
        $this->assertTrue($this->object->isValid($status));
    }
    /**
     *  househub\conditions\ObjectCondition::isValid
     */
    public function testIsNotValid() {
        $status = array();
        $status[] = new StatusStructure("status1", "statusValue");
        $status[] = new StatusStructure("status2", "otherValue");
        $status[] = new StatusStructure("status3", "anotherValue");
        $this->object->addRestriction("status1", "statusValue");
        $this->object->addRestriction("status2", "otherValue");
        $this->object->addRestriction("status3", "diferentValue");
        $this->assertFalse($this->object->isValid($status));
    }
    /**
     *  househub\conditions\ObjectCondition::isValid
     */
    public function testIsNotValid2() {
        $status = array();
        $status[] = new StatusStructure("status1", "statusValue");
        $this->object->addRestriction("status1", "statusValue");
        $this->object->addRestriction("status2", "otherValue");
        $this->object->addRestriction("status3", "diferentValue");
        $this->assertFalse($this->object->isValid($status));
    }

}
