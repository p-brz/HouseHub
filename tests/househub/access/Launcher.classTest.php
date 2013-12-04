<?php

namespace househub\access;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-04 at 15:42:01.
 */
class LauncherTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Launcher
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Launcher;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     *  househub\access\Launcher::launch
     * @dataProvider provider
     */
    public function testLaunch($strategyName) {
        $parameters = array(
            "method" => $strategyName
        );

        $answer = $this->object->launch($parameters);
        $this->assertNotNull($answer);
    }

    public function provider() {

        $filename = $this->getStrategyGuideIniPath();
        $strategiesNames = array_keys(parse_ini_file($filename));

        $arguments = array();

        foreach ($strategiesNames as $strategy) {
            if(!empty($strategy)){
                $arguments[] = array($strategy);
            }
        }

        var_dump($arguments);
        
        return $arguments;
    }
    
    public function getStrategyGuideIniPath(){
        $lastDir = __DIR__;
        while (!file_exists(getcwd() . "/.htroot")) {
            chdir('..');
        }
        $d = DIRECTORY_SEPARATOR;
        $filename = getcwd() . $d . "househub" . $d . "access" . $d . "strategyGuide.ini";
        
//        echo "filename: ".$filename . "\n";
        
        chdir($lastDir);
        
        return $filename;
    }

}
