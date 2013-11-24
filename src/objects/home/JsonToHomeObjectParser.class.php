<?php
namespace househub\objects\home;

use lightninghowl\utils\StrOpers;

use househub\services\parsers\JsonToServiceParser;

use househub\objects\parsers\JsonToObjectStructureParser;

use househub\objects\ObjectStructure;

use househub\status\parsers\JsonToStatusParser;

class JsonToHomeObjectParser{
	
	public function parse($jsonEntity, $address, $parentId = 0, $parentIndex = -1){
		$homeObject = new HomeObject();
		
		$structure = new ObjectStructure();
		$structureParser = new JsonToObjectStructureParser();
		$structure = $structureParser->parse($jsonEntity, $address, $parentId, $parentIndex);
		$homeObject->setStructure($structure);
		
		$services = array();
		$servicesJson = isset($jsonEntity['services']) ? $jsonEntity['services'] : null;
		if(!is_null($servicesJson)){
			$serviceParser = new JsonToServiceParser();
			foreach($servicesJson as $key=>$serviceJson){
				$services[] = $serviceParser->parse($key);
			}
			$homeObject->setServices($services);
		}
		
		$status = array();
		$statusJson = isset($jsonEntity['status']) ? $jsonEntity['status'] : null;
		if(!is_null($statusJson)){
			$statusParser = new JsonToStatusParser();
			foreach($statusJson as $key=>$loopStatus){
				$status[] = $statusParser->parse($key, $loopStatus);
			}
			$homeObject->setStatus($status);
		}
		
		$subObjectArray = array();
		$subObjects = isset($jsonEntity['objects']) ? $jsonEntity['objects'] : array();
		foreach($subObjects as $key=>$subObject){
			$subObjectArray[] = $this->parse($subObject, $address.'/objects/'.$key, 0, $key);
		}
		$homeObject->setSubObjects($subObjectArray);
		
		return $homeObject;
	}
	
}