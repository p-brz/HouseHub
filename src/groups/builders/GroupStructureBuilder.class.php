<?php 
namespace househub\groups\builders;

use lightninghowl\utils\date\Date;

use househub\groups\tables\GroupStructureTable;

use househub\groups\GroupStructure;

/**
 * Builds a group based on a statement fetch result (as an associative array)
 * 
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0
 *
 */
class GroupStructureBuilder{
	
	/**
	 * Builds the group
	 * 
	 * @param array $resource
	 * @return GroupStructure
	 */
	public function build($resource){
		$group = new GroupStructure();
		
		if(isset($resource[GroupStructureTable::COLUMN_ID])) $group->setId($resource[GroupStructureTable::COLUMN_ID]);
		if(isset($resource[GroupStructureTable::COLUMN_USER_ID])) $group->setUserId($resource[GroupStructureTable::COLUMN_USER_ID]);
		if(isset($resource[GroupStructureTable::COLUMN_REGISTRATION_DATE])) $group->setRegistrationDate($resource[GroupStructureTable::COLUMN_REGISTRATION_DATE]);
		
		return $group;
	}
	
}

?>