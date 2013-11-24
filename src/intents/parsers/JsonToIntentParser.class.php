<?php
namespace househub\intents\parsers;

use househub\intents\IntentStructure;

use househub\access\DatabaseConnector;

use househub\intents\dao\IntentStructureDAO;

use househub\intents\home\HomeIntent;

use househub\objects\ObjectStructure;

class JsonToIntentParser{
	
	public function parse($entity, $address, $parentId = 0, $parentIndex = -1){
		$homeIntent = new HomeIntent();
		
		$objectStructure = new ObjectStructure();
		
		$json = $entity;
		
		if(!is_array($json)){
			return null;
		}
		
		// Saving a connection intent
		$type = isset($json['configs']['type']) ? $json['configs']['type'] : null;
		$schemeName = isset($json['configs']['scheme']) ? $json['configs']['scheme'] : null;
		$subIntents = isset($json['objects']) ? $json['objects'] : array();
		
		$intentStructure = new IntentStructure();
		
		$intentStructure->setType($type);
		$intentStructure->setSchemeName($schemeName);
		$intentStructure->setAddress($address);
		$intentStructure->setParentId($parentId);
		$intentStructure->setParentIndex($parentIndex);
		
		$homeIntent->setStructure($intentStructure);
		
		$subIntentArray = array();
		foreach($subIntents as $key=>$subIntent){
			$subIntentArray[] = $this->parse($subIntent, $address.'/objects/'.$key, 0, $key);
		}
		
		$homeIntent->setSubIntents($subIntentArray);
		
		return $homeIntent;
	}
	
}