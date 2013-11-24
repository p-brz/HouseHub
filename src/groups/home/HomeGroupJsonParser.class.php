<?php
namespace househub\groups\home;

use househub\json\JsonData;

use househub\access\DatabaseConnector;

use househub\objects\home\HomeObjectManager;

use househub\json\JsonArray;

use househub\objects\home\HomeObjectJsonParser;

use househub\groups\parsers\GroupVisualJsonParser;

use househub\groups\parsers\GroupStructureJsonParser;

use househub\json\JsonObject;

class HomeGroupJsonParser{
	
	private $json;
	
	public function parse(HomeGroup $group, $withObjects = false){
		$this->json = new JsonObject();
		$this->json->addElement(new JsonData("kind", "BlockGroup"));
		// The structure
		$structure = $group->getStructure();
		$userId = $structure->getUserId();
		$structureParser = new GroupStructureJsonParser();
		$structureJson = $structureParser->parse($structure);
		$this->addElements($structureJson);
		
		// The visual
		$visual = $group->getVisual();
		$visualParser = new GroupVisualJsonParser();
		$visualJson = $visualParser->parse($visual);
		$visualJson->setName("visual");
		$this->json->addElement($visualJson);
		
		// The objects
		$objects = $group->getObjects();
		$manager = new HomeObjectManager();
		$homeObjects = array();
		foreach($objects as $object){
			$homeObjects[] = $manager->loadObject($object->getObjectId(), $userId, DatabaseConnector::getDriver());
		}
		
		$objectsParser = new HomeObjectJsonParser();
		$objectsArray = $this->buildJsonArray("objects", $homeObjects, $objectsParser);
		$this->json->addElement($objectsArray);
		
		return $this->json;
	}
	
	private function addElements(JsonObject $json){
		$elements = $json->getElements();
		foreach($elements as $element){
			$this->json->addElement($element);
		}
	}
	
	private function buildJsonArray($name, $structureArray, $parser){
		$array = new JsonArray($name);
		foreach($structureArray as $structure){
			$structureJson = $parser->parse($structure);
			$array->addElement($structureJson);
		}
		
		return $array;
	}
	
}