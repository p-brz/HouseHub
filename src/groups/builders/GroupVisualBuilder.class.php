<?php
namespace househub\groups\builders;

use househub\groups\tables\GroupVisualTable;

use househub\groups\GroupVisual;

/**
 * The builder of a group's visual aspects
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0
 */
class GroupVisualBuilder{
	
	/**
	 * Build the visual
	 * @param array $resource
	 * @return GroupVisual
	 */
	public function build($resource){
		$visual = new GroupVisual();
		
		if(isset($resource[GroupVisualTable::COLUMN_ID])) $visual->setId($resource[GroupVisualTable::COLUMN_ID]);
		if(isset($resource[GroupVisualTable::COLUMN_USER_ID])) $visual->setUserId($resource[GroupVisualTable::COLUMN_USER_ID]);
		if(isset($resource[GroupVisualTable::COLUMN_GROUP_ID])) $visual->setGroupId($resource[GroupVisualTable::COLUMN_GROUP_ID]);
		if(isset($resource[GroupVisualTable::COLUMN_GROUP_NAME])) $visual->setGroupName($resource[GroupVisualTable::COLUMN_GROUP_NAME]);
		if(isset($resource[GroupVisualTable::COLUMN_GROUP_IMAGE_ID])) $visual->setGroupImageId($resource[GroupVisualTable::COLUMN_GROUP_IMAGE_ID]);
		if(isset($resource[GroupVisualTable::COLUMN_SET_DATE])) $visual->setSetDate($resource[GroupVisualTable::COLUMN_SET_DATE]);
		
		return $visual;
	}
	
}

?>