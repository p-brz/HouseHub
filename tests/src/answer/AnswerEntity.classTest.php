<?php

namespace househub\answer;
require_once(__DIR__.'/../../../lightninghowl/utils/AutoLoader.class.php');
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-11-26 at 19:21:38.
 */
class AnswerEntityTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AnswerEntity
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new AnswerEntity;
        $this->object->setStatus(1);
        $this->object->setMessage('Message');
        $this->object->setContent(array());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * @covers househub\answer\AnswerEntity::getStatus
     */
    public function testGetStatus() {
        $objStatus = $this->object->getStatus();
        $this->assertEquals(1, $objStatus);
    }

    /**
     * @covers househub\answer\AnswerEntity::getMessage
     */
    public function testGetMessage() {
        $objMessage = $this->object->getMessage();
        $this->assertEquals('Message', $objMessage);
    }

    /**
     * @covers househub\answer\AnswerEntity::getContent
     */
    public function testGetContent() {
        $content = $this->object->getContent();
        $this->assertEquals(array(), $content);
    }

    /**
     * @covers househub\answer\AnswerEntity::setStatus
     */
    public function testSetStatus() {
        $this->object->setStatus(0);
        $this->assertEquals($this->object->getStatus(), 0);
    }

    /**
     * @covers househub\answer\AnswerEntity::setMessage
     */
    public function testSetMessage() {
        $this->object->setMessage('newMessage');
        $this->assertEquals('newMessage', $this->object->getMessage());
    }

    /**
     * @covers househub\answer\AnswerEntity::setContent
     */
    public function testSetContent() {
        $content = array('foo', 'bar');
        $this->object->setContent($content);
        $this->assertEquals($this->object->getContent(), $content);
    }

    /**
     * @covers househub\answer\AnswerEntity::addContent
     */
    public function testAddContent() {
        $content = array();
        $this->object->setContent($content);
        $content[] = 'foo';
        $this->object->addContent('foo');
        $this->assertEquals($this->object->getContent(), $content);
    }

}
