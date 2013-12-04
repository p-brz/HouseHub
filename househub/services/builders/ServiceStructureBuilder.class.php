<?php
namespace househub\services\builders;

use househub\services\tables\ServiceStructureTable;

use househub\services\ServiceStructure;

class ServiceStructureBuilder{
	
	public function build($resource){
		$service = new ServiceStructure();
		
		if(isset($resource[ServiceStructureTable::COLUMN_ID])) $service->setId($resource[ServiceStructureTable::COLUMN_ID]);
		if(isset($resource[ServiceStructureTable::COLUMN_OBJECT_ID])) $service->setObjectId($resource[ServiceStructureTable::COLUMN_OBJECT_ID]);
		if(isset($resource[ServiceStructureTable::COLUMN_NAME])) $service->setName($resource[ServiceStructureTable::COLUMN_NAME]);
		
		return $service;
	}
	
}