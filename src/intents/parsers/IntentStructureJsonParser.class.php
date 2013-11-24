<?php
namespace househub\intents\parsers;

use househub\json\JsonData;

use househub\intents\IntentStructure;

use househub\json\JsonObject;

class IntentStructureJsonParser{
	
	public function parse($entity){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!($entity instanceof IntentStructure)){
			return $json;
		}
		
		$json->addElement(new JsonData("id", $entity->getId()));
		$json->addElement(new JsonData("kind", "Intent"));
		$json->addElement(new JsonData("type", $entity->getType()));
		$json->addElement(new JsonData("address", $entity->getAddress()));
		$json->addElement(new JsonData("scheme", $entity->getSchemeName()));
		$json->addElement(new JsonData("reg_time", $entity->getRequestDate()));
		
		
		return $json;
	}
	
}


?>