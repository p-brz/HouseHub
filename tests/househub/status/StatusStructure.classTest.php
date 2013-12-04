<?php

namespace househub\status;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-04 at 01:26:32.
 */
class StatusStructureTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var StatusStructure
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new StatusStructure;
        $this->object->setId(1);
        $this->object->setName('status');
        $this->object->setObjectId(2);
        $this->object->setValue(3);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers househub\status\StatusStructure::getId
     * @todo   Implement testGetId().
     */
    public function testGetId() {
        $this->assertEquals(1, $this->object->getId());
    }

    /**
     * @covers househub\status\StatusStructure::getObjectId
     * @todo   Implement testGetObjectId().
     */
    public function testGetObjectId() {
        $this->assertEquals(2, $this->object->getObjectId());
    }

    /**
     * @covers househub\status\StatusStructure::getName
     * @todo   Implement testGetName().
     */
    public function testGetName() {
        $this->assertEquals('status', $this->object->getName());
    }

    /**
     * @covers househub\status\StatusStructure::getValue
     * @todo   Implement testGetValue().
     */
    public function testGetValue() {
        $this->assertEquals(3, $this->object->getValue());
    }

    /**
     * @covers househub\status\StatusStructure::setId
     * @todo   Implement testSetId().
     */
    public function testSetId() {
        $this->object->setId(2);
        $this->assertEquals(2, $this->object->getId());
    }

    /**
     * @covers househub\status\StatusStructure::setObjectId
     * @todo   Implement testSetObjectId().
     */
    public function testSetObjectId() {
        $this->object->setObjectId(2);
        $this->assertEquals(2, $this->object->getObjectId());
    }

    /**
     * @covers househub\status\StatusStructure::setName
     * @todo   Implement testSetName().
     */
    public function testSetName() {
        $this->object->setName('name');
        $this->assertEquals('name', $this->object->getName());
    }

    public function testSetValue() {
        $object = new StatusStructure();
        $object->setValue(100);
        $this->assertEquals(100, $object->getValue());
    }

}