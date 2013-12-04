<?php

namespace househub\access\strategies\session;

require_once(realpath(__DIR__ . DIRECTORY_SEPARATOR . "LoginHelper.php"));

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-02 at 02:25:50.
 */
class LogoutAccessStrategyTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var LogoutAccessStrategy
     */
    protected $object;
    private $loginHelper;
    /**
     * @group cookie
     */
    protected function setUp() {
        $this->object = new LogoutAccessStrategy;
        $this->loginHelper = new LoginHelper();
    }
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * househub\access\strategies\session\LogoutAccessStrategy::requestAccess
     * @group cookie
     */
    public function testRequestAccessLogout() {
        $this->loginHelper->doLogin();
        // Remove the following lines when you implement this test.
        $answer = $this->object->requestAccess(array("method"=>"logout"));
        $this->assertEquals(1,$answer->getStatus());
    }

}