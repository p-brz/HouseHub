<?php
namespace househub\groups\builders;

use househub\groups\tables\GroupElementsTable;

use househub\groups\GroupElement;

class GroupElementBuilder{
	
	public function build($resource){
		$element = new GroupElement();
		
		$element->setId(isset($resource[GroupElementsTable::COLUMN_ID]) ? $resource[GroupElementsTable::COLUMN_ID] : null);
		$element->setGroupId(isset($resource[GroupElementsTable::COLUMN_GROUP_ID]) ? $resource[GroupElementsTable::COLUMN_GROUP_ID] : null);
		$element->setObjectId(isset($resource[GroupElementsTable::COLUMN_ELEMENT_ID]) ? $resource[GroupElementsTable::COLUMN_ELEMENT_ID] : null);
		$element->setAdditionDate(isset($resource[GroupElementsTable::COLUMN_ADDITION_DATE]) ? $resource[GroupElementsTable::COLUMN_ADDITION_DATE] : null);
		
		return $element;
		
	}
	
}