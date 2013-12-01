<?php
namespace househub\status\parsers;

use househub\json\JsonData;

use househub\json\JsonObject;

use househub\status\StatusStructure;

class StatusStructureJsonParser{
	
	public function parse($entity){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!$entity instanceof StatusStructure){
			return $json;
		}
		
		$json->addElement(new JsonData("id", $entity->getId()));
		$json->addElement(new JsonData("name", $entity->getName()));
		$json->addElement(new JsonData("value", $entity->getValue()));
		
		
		return $json;
	}
	
}

?>