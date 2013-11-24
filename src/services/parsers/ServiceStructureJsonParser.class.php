<?php
namespace househub\services\parsers;

use lightninghowl\utils\encrypting\Base64Reverse;

use househub\json\JsonData;

use househub\services\ServiceStructure;

use househub\json\JsonObject;

class ServiceStructureJsonParser{
	
	public function parse($entity){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!($entity instanceof ServiceStructure)){
			return $json;
		}
		
		$encoder = new Base64Reverse();
		$json->addElement(new JsonData("id", $encoder->encrypt($entity->getId())));
		$json->addElement(new JsonData("name", $entity->getName()));
		
		return $json;
	}
	
}

?>