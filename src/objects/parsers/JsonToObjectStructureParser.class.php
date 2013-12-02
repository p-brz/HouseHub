<?php
namespace househub\objects\parsers;

use lightninghowl\utils\StrOpers;

use househub\objects\dao\ObjectStructureDAO;

use househub\objects\ObjectStructure;

use househub\objects\home\HomeObject;

class JsonToObjectStructureParser{
	
	public function parse($entity, $address, $parentId = 0, $parentIndex = -1){
		$objectStructure = new ObjectStructure();
		
		if(!is_array($entity)){
			return null;
		}
		
		$type = isset($entity['configs']['type']) ? $entity['configs']['type'] : null;
		$schemeName = isset($entity['configs']['scheme']) ? $entity['configs']['scheme'] : null;
		
		$objectStructure->setType($type);
		$objectStructure->setAddress($address);
		$objectStructure->setSchemeName($schemeName);
		$objectStructure->setParentId($parentId);
		$objectStructure->setParentIndex($parentIndex);
		$objectStructure->setValidated(1);
		$objectStructure->setConnected(1);
		
		return $objectStructure;
	}
	
}

?>