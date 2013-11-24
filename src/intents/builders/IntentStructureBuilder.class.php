<?php
namespace househub\intents\builders;

use househub\intents\tables\IntentStructureTable;

use househub\intents\IntentStructure;

class IntentStructureBuilder{
	
	public function build($resource){
		$intent = new IntentStructure();
		
		if(isset($resource[IntentStructureTable::COLUMN_ID])) $intent->setId($resource[IntentStructureTable::COLUMN_ID]);
		if(isset($resource[IntentStructureTable::COLUMN_TYPE])) $intent->setType($resource[IntentStructureTable::COLUMN_TYPE]);
		if(isset($resource[IntentStructureTable::COLUMN_ADDRESS])) $intent->setAddress($resource[IntentStructureTable::COLUMN_ADDRESS]);
		if(isset($resource[IntentStructureTable::COLUMN_SCHEME_NAME])) $intent->setSchemeName($resource[IntentStructureTable::COLUMN_SCHEME_NAME]);
		if(isset($resource[IntentStructureTable::COLUMN_PARENT_ID])) $intent->setParentId($resource[IntentStructureTable::COLUMN_PARENT_ID]);
		if(isset($resource[IntentStructureTable::COLUMN_PARENT_INDEX])) $intent->setParentIndex($resource[IntentStructureTable::COLUMN_PARENT_INDEX]);
		if(isset($resource[IntentStructureTable::COLUMN_REQUEST_DATE])) $intent->setRequestDate($resource[IntentStructureTable::COLUMN_REQUEST_DATE]);
		
		return $intent;
	}
	
}

?>