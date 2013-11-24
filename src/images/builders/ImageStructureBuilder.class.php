<?php

namespace househub\images\builders;

use househub\images\tables\ImageStructureTable;

use househub\images\ImageStructure;

class ImageStructureBuilder{
	
	public function build($resource){
		$image = new ImageStructure();
		
		if(isset($resource[ImageStructureTable::COLUMN_ID])) $image->setId($resource[ImageStructureTable::COLUMN_ID]);
		if(isset($resource[ImageStructureTable::COLUMN_USER_ID])) $image->setUserId($resource[ImageStructureTable::COLUMN_USER_ID]);
		if(isset($resource[ImageStructureTable::COLUMN_IMAGE_NAME])) $image->setName($resource[ImageStructureTable::COLUMN_IMAGE_NAME]);
		if(isset($resource[ImageStructureTable::COLUMN_RESOURCE])) $image->setResource($resource[ImageStructureTable::COLUMN_RESOURCE]);
		if(isset($resource[ImageStructureTable::COLUMN_REGISTRATION_DATE])) $image->setRegistrationDate($resource[ImageStructureTable::COLUMN_REGISTRATION_DATE]);
		
		return $image;
	}
	
}

?>