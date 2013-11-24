<?php
namespace househub\abstracts;

use househub\json\JsonArray;

use househub\json\JsonObject;

class AbstractHomeJsonParser{
	
	protected $json;
	
	protected function addElements(JsonObject $json){
		$elements = $json->getElements();
		foreach($elements as $element){
			$this->json->addElement($element);
		}
	}
	
	protected function buildJsonArray($name, $structureArray, $parser){
		$array = new JsonArray($name);
		foreach($structureArray as $structure){
			$structureJson = $parser->parse($structure);
			$array->addElement($structureJson);
		}
		
		return $array;
	}
	
}