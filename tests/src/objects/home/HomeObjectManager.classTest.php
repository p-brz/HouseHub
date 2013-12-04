<?php

namespace househub\objects\home;

use \househub\objects\ObjectStructure;
use \househub\services\ServiceStructure;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-03 at 17:23:12.
 */
class HomeObjectManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var HomeObjectManager
     */
    protected $object;

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new HomeObjectManager();
        $this->pdo = \househub\access\DatabaseConnector::getDriver();
        $this->pdo->beginTransaction();
    }

    protected function tearDown() {

        $this->pdo->rollBack();
    }

    /**
     *  househub\objects\home\HomeObjectManager::saveObject
     */
    public function testSaveObject() {
//        $homeObject = $this->makeObject();
//        $savedObject = $this->object->saveObject($homeObject, $this->pdo);
        
        
    }

    /**
     *  househub\objects\home\HomeObjectManager::loadObject
     * @todo   Implement testLoadObject().
     */
    public function testLoadObject() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     *  househub\objects\home\HomeObjectManaobjectger::loadStructure
     */
    public function testLoadStructure() {
        $structure = $this->makeStructure();
        $savedStructure = $this->object->saveObjectStructure($structure, $this->pdo);
        $loadedStructure = $this->object->loadStructure($savedStructure->getId(), $this->pdo);
        
        $this->assertNotNull($loadedStructure);
        $this->assertEquals($savedStructure,$loadedStructure);
    }
    /**
     *  househub\objects\home\HomeObjectManaobjectger::loadStructure
     */
    public function testLoadStructureFail() {
        $loadedStructure = $this->object->loadStructure(null, $this->pdo);
        
        $this->assertNull($loadedStructure);
    }

    /**
     *  househub\objects\home\HomeObjectManager::loadVisualName
     * @todo   Implement testLoadVisualName().
     */
    public function testLoadVisualName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     *  househub\objects\home\HomeObjectManager::loadVisualIconpack
     * @todo   Implement testLoadVisualIconpack().
     */
    public function testLoadVisualIconpack() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     *  househub\objects\home\HomeObjectManager::saveServices
     * @todo   Implement testLoadServices().
     */
    public function testSaveServices() {         
        $structure = $this->makeStructure();
        
        $structureDAO = new \househub\objects\dao\ObjectStructureDAO($this->pdo);
        $structureId = $structureDAO->insert($structure);
        
        $services = $this->makeServices($structureId);        
        
        $savedServices = $this->object->saveServices($structureId, $services, $this->pdo);
        
        $servicesDAO = new \househub\services\dao\ServiceStructureDAO($this->pdo);
        $loadedServices = array();
        foreach ($savedServices as $service){
            $loadedServices[] = $servicesDAO->load($service->getId());
        }
        
        $this->assertEquals($loadedServices, $savedServices);
        
        return array($structureId, $loadedServices);
    }
    /**
     *  househub\objects\home\HomeObjectManager::loadServices
     * @depends testSaveServices
     */
    public function testLoadServices($data) { 
        $objId = $data[0];
        $expectedServices = $data[1];
        
        $this->object->saveServices($objId, $expectedServices, $this->pdo);
        $loadedServices = $this->object->loadServices($objId, $this->pdo);
        
        $this->assertEquals($expectedServices,$loadedServices);
    }

    /**
     *  househub\objects\home\HomeObjectManager::loadStatus
     */
    public function testSaveStatus() {  
        $structure = $this->makeStructure();
        
        $structureDAO = new \househub\objects\dao\ObjectStructureDAO($this->pdo);
        $structureId = $structureDAO->insert($structure);
        
        $services = $this->makeStatus($structureId);        
        
        $savedStatus = $this->object->saveStatus($structureId, $services, $this->pdo);
        
        $statusDAO = new \househub\status\dao\StatusStructureDAO($this->pdo);
        $loadedStatus = array();
        foreach ($savedStatus as $status){
            $loadedStatus[] = $statusDAO->load($status->getId());
        }
        
        $this->assertEquals($loadedStatus, $savedStatus);
        
        return array($structureId, $loadedStatus);
    }
    /**
     *  househub\objects\home\HomeObjectManager::loadStatus
     * @depends testSaveStatus
     */
    public function testLoadStatus($data) {
        $objId = $data[0];
        $expectedStatus = $data[1];
        
        $this->object->saveStatus($objId, $expectedStatus, $this->pdo);
        $loadedStatus = $this->object->loadStatus($objId, $this->pdo);
        
        $this->assertEquals($expectedStatus,$loadedStatus);
    }
    
    protected function makeStatus(){  
//        {
//            "name" : "aberta",
//            "path" : "/status/aberta",
//            "type" : "boolean",
//            "readOnly" : true
//        },
//        {
//            "name" : "travada",
//            "path" : "/status/travada",
//            "type" : "boolean",
//            "readOnly" : true
//        }
        $statusAberta = new \househub\status\StatusStructure("aberta", 0);
        $statusFechada = new \househub\status\StatusStructure("fechada", 0);
        return array($statusAberta, $statusFechada);
    }

    /**
     *  househub\objects\home\HomeObjectManager::loadSubObjects
     * @todo   Implement testLoadSubObjects().
     */
    public function testLoadSubObjects() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     *  househub\objects\home\HomeObjectManager::loadScheme
     * @todo   Implement testLoadScheme().
     */
    public function testLoadScheme() {
//        $structure = new ObjectStructure();
//        $structure->setAddress("http://192.168.0.100");
//        $structure->setSchemeName("basicDoor");
        $structure = $this->makeStructure();
        $structure->setSchemeName("basicDoor");
        $scheme = $this->object->loadScheme($structure);
        
        $this->assertNotNull($scheme);
        $this->assertEquals("basicDoor",$scheme->getSchemeName());
    }


    /**
     * @return HomeObject
     */
    private function makeObject() {
        $homeObject = new HomeObject();
        $structure = new ObjectStructure();
        $structure->setAddress("http://192.168.0.1");
        $structure->setSchemeName("basicDoor");
        $structure->setConnected(false);
        $structure->setType("door");
        
        $homeObject->setStructure($structure);
        
        
        return $homeObject;
    }
    
    /**
     * @return ObjectStructure
     */
    private function makeStructure(){
        $structure = new ObjectStructure();
        $structure->setAddress("http://192.168.0.100");
        $structure->setSchemeName("basicDoor");
        $structure->setConnected(false);
        $structure->setType("door");
        
        return $structure;
    }
    
    
    /**

        {
            "name"  : "travar",
            "path"  : "/services/travar",
            "returnType" : "boolean",
            "text"  : "@travar",
            "undoService" : "destravar",
            "parameters" : []
        },,
        {
            "name"  : "destravar",
            "path"  : "/services/destravar",
            "returnType" : "boolean",
            "text"  : "@destravar",
            "undoService" : "travar",
            "parameters" : []
        }
     *      */
    
    /**
     * @return ObjectStructure
     */
    private function makeServices($objId = 1){        
        $service1 = new ServiceStructure();
        $service1->setName("travar");
        $service1->setObjectId($objId);
        
        $service2 = new ServiceStructure();
        $service2->setName("destravar");
        $service2->setObjectId($objId);
        
        return array($service1, $service2);
    }
}
