<?php

namespace househub\objects\builders;

use househub\objects\ObjectStructure;

use househub\objects\tables\ObjectStructureTable;

class ObjectStructureBuilder{
	
	public function build($resource){
		$structure = new ObjectStructure();
		
		if(isset($resource[ObjectStructureTable::COLUMN_ID])) $structure->setId((int)$resource[ObjectStructureTable::COLUMN_ID]);
		if(isset($resource[ObjectStructureTable::COLUMN_TYPE])) $structure->setType($resource[ObjectStructureTable::COLUMN_TYPE]);
		if(isset($resource[ObjectStructureTable::COLUMN_ADDRESS])) $structure->setAddress($resource[ObjectStructureTable::COLUMN_ADDRESS]);
		if(isset($resource[ObjectStructureTable::COLUMN_SCHEME_NAME])) $structure->setSchemeName($resource[ObjectStructureTable::COLUMN_SCHEME_NAME]);
		if(isset($resource[ObjectStructureTable::COLUMN_PARENT_ID])) $structure->setParentId($resource[ObjectStructureTable::COLUMN_PARENT_ID]);
		if(isset($resource[ObjectStructureTable::COLUMN_PARENT_INDEX])) $structure->setParentIndex($resource[ObjectStructureTable::COLUMN_PARENT_INDEX]);
		if(isset($resource[ObjectStructureTable::COLUMN_VALIDATED])) $structure->setValidated($resource[ObjectStructureTable::COLUMN_VALIDATED]);
		if(isset($resource[ObjectStructureTable::COLUMN_CONNECTED])) $structure->setConnected($resource[ObjectStructureTable::COLUMN_CONNECTED]);
		if(isset($resource[ObjectStructureTable::COLUMN_REGISTRATION_DATE])) $structure->setRegistrationDate($resource[ObjectStructureTable::COLUMN_REGISTRATION_DATE]);
		
		return $structure;
	}
	
}

?>