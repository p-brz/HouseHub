<?php

namespace lightninghowl\utils\sql;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-03 at 07:13:51.
 */
class MultiInsertTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var MultiInsert
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new MultiInsert();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers lightninghowl\utils\sql\MultiInsert::setColumns
     * @todo   Implement testSetColumns().
     */
    public function testSetColumns() {
        $object = new MultiInsert();
        $object->setEntity('entity');
        $object->setColumns('id', 'name');
        $this->assertEquals('INSERT INTO entity (id, name) VALUES ', $object->getInstruction());
    }

    /**
     * @covers lightninghowl\utils\sql\MultiInsert::addColumnValues
     * @todo   Implement testAddColumnValues().
     */
    public function testAddColumnValues() {
        $object = new MultiInsert();
        $object->setEntity('entity');
        $object->setColumns('id', 'name');

        $object->addColumnValues(1, true);
        $object->addColumnValues(2, 2);
        $object->addColumnValues(3, 'lol');
        $this->assertEquals('INSERT INTO entity (id, name) VALUES (1, TRUE), (2, 2), (3, \'lol\')', $object->getInstruction());
    }

    /**
     * @expectedException Exception
     */
    public function testAddColumValuesException() {
        try {
            $object = new MultiInsert();
            $object->setEntity('entity');
            $object->setColumns('id', 'name');

            $object->addColumnValues(1, 1, 1);
            $this->assertTrue(false);
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }
    }

    /**
     * @covers lightninghowl\utils\sql\MultiInsert::getInstruction
     * @todo   Implement testGetInstruction().
     */
    public function testGetInstruction() {
        $object = new MultiInsert();
        $object->setEntity('entity');
        $object->setColumns('id', 'name');
        $object->addColumnValues(1, 'test');
        $object->addColumnValues(2, 'sub');
        $this->assertEquals('INSERT INTO entity (id, name) VALUES (1, \'test\'), (2, \'sub\')', $object->getInstruction());
    }

}