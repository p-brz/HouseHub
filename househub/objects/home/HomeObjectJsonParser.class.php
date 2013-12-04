<?php

namespace househub\objects\home;

use househub\abstracts\AbstractHomeJsonParser;
use househub\json\JsonData;
use househub\json\JsonObject;
use househub\objects\parsers\ObjectStructureJsonParser;
use househub\objects\parsers\ObjectVisualNameJsonParser;
use househub\readers\SchemeDictionary;
use househub\services\parsers\ServiceStructureJsonParser;
use househub\status\parsers\StatusStructureJsonParser;

class HomeObjectJsonParser extends AbstractHomeJsonParser{
	
	public function parse(HomeObject $object, $withSubObjects = false){
		$this->json = new JsonObject();
		
		// Loading the object structure and adding it to the json
		$structure = $object->getStructure();
		$structureParser = new ObjectStructureJsonParser();
		$structureJson = $structureParser->parse($structure);
		$this->addElements($structureJson);
		$this->json->addElement(new JsonData("schemeName", $structure->getSchemeName()));
		
		// Now to the visual aspects
		$visualJson = new JsonObject("visual");
		
		// First, the name
		$visualName = $object->getVisualName();
		$visualNameParser = new ObjectVisualNameJsonParser();
		$visualNameJson = $visualNameParser->parse($visualName);
		
		$setted = false;
		foreach($visualNameJson->getElements() as $element){
			if($element->getName() == 'name'){
				$visualJson->addElement($element);
				$setted = true;
			}
		}
		
		if(!$setted){
			$visualJson->addElement(new JsonData("name", ""));
		}
		
//		// We'll need this little boy later
//		$conditions = $object->getValidConditions();
//		$condition = (is_array($conditions) && !empty($conditions)) ? $conditions[0] : null;
//		
//		// Now to the iconpack
//		$visualIconpack = $object->getVisualIconpack();
//		$visualIconpackParser = new ObjectVisualIconpackJsonParser();
//		$visualIconpackJson = $visualIconpackParser->parse($visualIconpack, $condition);
//		
//		$setted = false;
//		foreach($visualIconpackJson->getElements() as $element){
//			if($element->getName() == 'image'){
//				$visualJson->addElement($element);
//				$setted = true;
//			}
//		}
//		
//		if(!$setted){
//			$visualJson->addElement(new JsonData("image", null));
//		}
		
		// Finally, to the state!
		$state = null;
		$setted = false;
		if(!is_null($object->getScheme())){
			$schDic = new SchemeDictionary($object->getScheme());
			$state = $schDic->translate($condition->getName());
			
			
			$visualJson->addElement(new JsonData("condition", $state));
			$setted = true;
		}
		
		if(!$setted){
			$visualJson->addElement(new JsonData("condition", null));
		}
		
		$this->json->addElement($visualJson);
		
		// The services (there will be more than 1)
		$services = $object->getServices();
		$serviceParser = new ServiceStructureJsonParser();
		$serviceJsonArray = $this->buildJsonArray("services", $services, $serviceParser);
		$this->json->addElement($serviceJsonArray);
		
		// The status (there will be more than 1)
		$statusArray = $object->getStatus();
		$statusParser = new StatusStructureJsonParser();
		$serviceJsonArray = $this->buildJsonArray("status", $statusArray, $statusParser);
		$this->json->addElement($serviceJsonArray);
		
		// The sub objects (if needed)
		if($withSubObjects){
			$subObjects = $object->getSubObjects();
			$subObjectsArray = $this->buildJsonArray("objects", $subObjects, new HomeObjectJsonParser());
			$this->json->addElement($subObjectsArray);
		}
		
		return $this->json;
	}
	
}