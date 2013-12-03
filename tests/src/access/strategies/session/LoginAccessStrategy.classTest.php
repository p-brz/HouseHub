<?php

namespace househub\access\strategies\session;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-01 at 20:51:14.
 */
class LoginAccessStrategyTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var LoginAccessStrategy
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new LoginAccessStrategy;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @group cookie
     */
    public function testRequestAccess() {
        $parameters = array(
            "method" => "login",
            "username" => "adm",
            "password" => "123456"
        );
        $answer = $this->object->requestAccess($parameters); 
       
        $this->assertNotNull($answer->getContent()->getElement("user_rights"));
        $this->assertNotNull($answer->getContent()->getElement("phpsessid"));        
    }
    
    
    
    /**
     */
    public function testRequestFail() {
        $parameters = array(
            "method" => "login",
            "username" => "usuarioInexistente",
            "password" => "123456"
        );
        $answer = $this->object->requestAccess($parameters); 
        
        $this->assertEquals(0, $answer->getStatus());
//        $this->assertNotNull($answer->getContent()->getElement("user_rights"));
//        $this->assertNotNull($answer->getContent()->getElement("phpsessid"));
    }
    /**
     * 
     */
    public function testRequestWithoutUser() {
        $parameters = array(
            "method" => "login",
            "password" => "123456"
        );
        $answer = $this->object->requestAccess($parameters); 
        
        $this->assertEquals(0, $answer->getStatus());
    }
    /**
     */
    public function testRequestWithoutPass() {
        $parameters = array(
            "method" => "login",
            "username" => "usuarioInexistente"
        );
        $answer = $this->object->requestAccess($parameters); 
        
        $this->assertEquals(0, $answer->getStatus());
    }

}
