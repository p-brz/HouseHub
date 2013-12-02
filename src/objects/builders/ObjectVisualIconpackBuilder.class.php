<?php
namespace househub\objects\builders;

use househub\objects\tables\ObjectVisualIconpackTable;

use househub\objects\ObjectVisualIconpack;

class ObjectVisualIconpackBuilder{
	
	public function build($resource){
		if(!is_array($resource)){
			return null;
		}
		
		$visual = new ObjectVisualIconpack();
		
		if(isset($resource[ObjectVisualIconpackTable::COLUMN_ID])) $visual->setId($resource[ObjectVisualIconpackTable::COLUMN_ID]);
		if(isset($resource[ObjectVisualIconpackTable::COLUMN_USER_ID])) $visual->setUserId($resource[ObjectVisualIconpackTable::COLUMN_USER_ID]);
		if(isset($resource[ObjectVisualIconpackTable::COLUMN_OBJECT_ID])) $visual->setObjectId($resource[ObjectVisualIconpackTable::COLUMN_OBJECT_ID]);
		if(isset($resource[ObjectVisualIconpackTable::COLUMN_ICONPACK_ID])) $visual->setIconpackId($resource[ObjectVisualIconpackTable::COLUMN_ICONPACK_ID]);
		if(isset($resource[ObjectVisualIconpackTable::COLUMN_SET_DATE])) $visual->setSetDate($resource[ObjectVisualIconpackTable::COLUMN_SET_DATE]);
		
		return $visual;
		
	}
	
}