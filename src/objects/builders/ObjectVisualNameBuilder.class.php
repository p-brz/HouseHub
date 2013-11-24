<?php
namespace househub\objects\builders;

use househub\objects\tables\ObjectVisualNameTable;

use househub\objects\ObjectVisualName;

class ObjectVisualNameBuilder{
	
	public function build($resource){
		if(!is_array($resource)){
			return null;
		}
		
		$visual = new ObjectVisualName();
		
		if(isset($resource[ObjectVisualNameTable::COLUMN_ID])) $visual->setId($resource[ObjectVisualNameTable::COLUMN_ID]);
		if(isset($resource[ObjectVisualNameTable::COLUMN_USER_ID])) $visual->setUserId($resource[ObjectVisualNameTable::COLUMN_USER_ID]);
		if(isset($resource[ObjectVisualNameTable::COLUMN_OBJECT_ID])) $visual->setObjectId($resource[ObjectVisualNameTable::COLUMN_OBJECT_ID]);
		if(isset($resource[ObjectVisualNameTable::COLUMN_OBJECT_NAME])) $visual->setObjectName($resource[ObjectVisualNameTable::COLUMN_OBJECT_NAME]);
		if(isset($resource[ObjectVisualNameTable::COLUMN_SET_DATE])) $visual->setSetDate($resource[ObjectVisualNameTable::COLUMN_SET_DATE]);
		
		return $visual;
		
	}
	
}