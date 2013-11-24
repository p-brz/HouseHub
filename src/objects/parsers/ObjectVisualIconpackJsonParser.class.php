<?php
namespace househub\objects\parsers;

use househub\access\DatabaseConnector;

use househub\iconpacks\dao\IconpackStructureDAO;

use househub\json\JsonData;

use househub\json\JsonObject;

use househub\objects\ObjectVisualIconpack;

class ObjectVisualIconpackJsonParser{
	
	public function parse($entity, $validCondition = null){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!($entity instanceof ObjectVisualIconpack)){
			return $json;
		}
		
		$imagePath = null;
		if(!is_null($validCondition)){
			$icondao = new IconpackStructureDAO(DatabaseConnector::getDriver());
			$iconpack = $icondao->load($entity->getIconpackId());
			
			if(!is_null($iconpack)){
				$dic = $iconpack->getDictionary();
				$imagePath = isset($dic[$validCondition->getName()]) ? $dic[$validCondition->getName()] : null;
			}
		}
		
		$json->addElement(new JsonData("id", $entity->getId()));
		$json->addElement(new JsonData("image", $imagePath));
		
		return $json;
	}
	
}