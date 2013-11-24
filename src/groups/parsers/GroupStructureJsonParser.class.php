<?php
namespace househub\groups\parsers;

use househub\json\JsonData;

use househub\json\JsonObject;

use househub\groups\GroupStructure;

class GroupStructureJsonParser{
	
	public function parse($entity){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!($entity instanceof GroupStructure)){
			return $json;
		}
		
		$json->addElement(new JsonData("id", $entity->getId()));
		$json->addElement(new JsonData("owner", $entity->getUserId()));
		$json->addElement(new JsonData("reg_time", $entity->getRegistrationDate()));
		
		return $json;
	}
	
}
?>