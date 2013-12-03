<?php

namespace lightninghowl\utils\url;

require_once realpath(dirname(__FILE__) . '/../../../../lightninghowl/utils/AutoLoader.class.php');
//$d = DIRECTORY_SEPARATOR;
//require_once dirname(__FILE__).$d . '..' . $d . '..' . $d . '..' . $d . 'lightninghowl'. $d .'utils' . $d . 'AutoLoader.class.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-11-28 at 10:27:05.
 */
class UrlHandlerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var UrlHandler
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new UrlHandler('http://localhost', 'GET');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * lightninghowl\utils\url\UrlHandler::setTimeout
     * @todo   Implement testSetTimeout().
     */
    public function testSetTimeout() {
        $this->object->setTimeout(30);
        $this->assertEquals(30, $this->object->getTimeout());
    }

    /**
     * lightninghowl\utils\url\UrlHandler::getTimeout
     * @todo   Implement testGetTimeout().
     */
    public function testGetTimeout() {
        $this->object->setTimeout(30);
        $this->assertEquals(30, $this->object->getTimeout());
    }

    /**
     * lightninghowl\utils\url\UrlHandler::getContent
     * @todo   Implement testGetContent().
     */
    public function testGetContent() {
        $this->assertEquals(null, $this->object->getContent());
        $this->object->run();
        $this->assertNotNull($this->object->getContent());
    }

    /**
     * lightninghowl\utils\url\UrlHandler::getHeader
     * @todo   Implement testGetHeader().
     */
    public function testGetHeader() {
        $this->object->run();
        $this->assertNotNull($this->object->getHeader());
    }

    /**
     * lightninghowl\utils\url\UrlHandler::getStatus
     * @todo   Implement testGetStatus().
     */
    public function testGetStatus() {
        $this->object->run();
        $this->assertNotNull($this->object->getStatus());
        $this->assertTrue($this->object->getStatus()!=0);
    }

    /**
     * lightninghowl\utils\url\UrlHandler::addField
     * @todo   Implement testAddField().
     */
    public function testAddField() {
        $this->object->addField('foo', 'bar');
        $url = $this->object->run();
        $this->assertEquals('http://localhost/?foo=bar', $url);
    }

    /**
     * lightninghowl\utils\url\UrlHandler::run
     * @group no-travis
     */
    public function testRunGet() {
        $handler = new UrlHandler('http://localhost', 'GET');
        $this->assertEmpty($handler->getContent());
        $this->assertEmpty($handler->getStatus());
        $handler->run();
        $this->assertNotEmpty($handler->getContent());
        $this->assertNotEmpty($handler->getStatus());
    }
    
    /**
     * lightninghowl\utils\url\UrlHandler::run
     * @group no-travis
     */
    public function testRunPost(){
        var_dump(__DIR__);
        $handler = new UrlHandler('http://localhost/', 'POST');
        $this->assertEmpty($handler->getContent());
        $this->assertEmpty($handler->getStatus());
        $handler->run();
        $this->assertNotEmpty($handler->getContent());
        $this->assertNotEmpty($handler->getStatus());
    }

}
