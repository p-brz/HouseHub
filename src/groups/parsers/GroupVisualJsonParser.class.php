<?php
namespace househub\groups\parsers;

use lightninghowl\utils\StrOpers;

use househub\readers\SystemReader;

use househub\images\ImageStructure;

use househub\access\DatabaseConnector;

use househub\images\dao\ImageStructureDAO;

use househub\json\JsonData;

use househub\groups\GroupVisual;

use househub\json\JsonObject;

class GroupVisualJsonParser{
	
	public function parse($entity){
		$json = new JsonObject();
		
		if(is_null($entity)){
			return $json;
		}else if(!($entity instanceof GroupVisual)){
			return $json;
		}
		
		$imagePath = null;
		$imagedao = new ImageStructureDAO(DatabaseConnector::getDriver());
		$image = $imagedao->load($entity->getGroupImageId());
		
		if(!is_null($image)){
			$sysRes = SystemReader::getInstance();
			$imagePath  = $sysRes->translate(SystemReader::INDEX_UPLOADS);
			$imagePath .= '/'.$image->getResource();

			$imagePath = StrOpers::strFixPath($imagePath);
		}
		
		$json->addElement(new JsonData("id", $entity->getId()));
		$json->addElement(new JsonData("name", $entity->getGroupName()));
		$json->addElement(new JsonData("image", $imagePath));
		
		return $json;
	}
	
}

?>