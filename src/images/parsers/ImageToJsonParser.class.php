<?php

namespace househub\images\parsers;

use househub\json\JsonObject;

use househub\json\JsonData;

use househub\images\ImageStructure;

class ImageToJsonParser{
	
	public function imageToJson(ImageStructure $image){
		$jsonObject = new JsonObject();
		
		$jsonObject->addElement(new JsonData("id", $image->getId()));
		$jsonObject->addElement(new JsonData("name", $image->getName()));
		$jsonObject->addElement(new JsonData("resource", $image->getResource()));
		$jsonObject->addElement(new JsonData("entry_date", $image->getRegistrationDate()));
		
		return $jsonObject;
	}
	
}

?>