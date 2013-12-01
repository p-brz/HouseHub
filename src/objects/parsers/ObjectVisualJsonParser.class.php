<?php
namespace househub\objects\parsers;

use lightninghowl\utils\StrOpers;

use househub\access\DatabaseConnector;

use househub\iconsets\dao\IconsetStructureDAO;

use househub\iconsets\IconsetStructure;

use househub\json\JsonData;

use househub\objects\ObjectVisual;

use househub\json\JsonObject;

class ObjectVisualJsonParser{
	
	public function parse($entity, $validCondition = null){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!($entity instanceof ObjectVisual)){
			return $json;
		}
		
		$imagePath = null;
		if(!is_null($validCondition)){
			$icondao = new IconsetStructureDAO(DatabaseConnector::getDriver());
			$iconset = $icondao->load($entity->getIconsetId());
			
			if(!is_null($iconset)){
				$dic = $iconset->getDictionary();
				$imagePath = isset($dic[$validCondition->getName()]) ? $dic[$validCondition->getName()] : null;
			}
		}
		
		$json->addElement(new JsonData("id", $entity->getId()));
		$json->addElement(new JsonData("name", $entity->getObjectName()));
		$json->addElement(new JsonData("image", $imagePath));
		
		return $json;
	}
	
}