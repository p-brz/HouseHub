<?php
namespace househub\users\builders;

use househub\users\tables\UserStructureTable;

use househub\users\UserStructure;

class UserStructureBuilder{
	
	public function build($resource){
		if(!is_array($resource)){
			return null;
		}
		
		$user = new UserStructure();
		
		if(isset($resource[UserStructureTable::COLUMN_ID])) $user->setId($resource[UserStructureTable::COLUMN_ID]);
		if(isset($resource[UserStructureTable::COLUMN_NAME])) $user->setName($resource[UserStructureTable::COLUMN_NAME]);
		if(isset($resource[UserStructureTable::COLUMN_NICKNAME])) $user->setNickname($resource[UserStructureTable::COLUMN_NICKNAME]);
		if(isset($resource[UserStructureTable::COLUMN_GENDER])) $user->setGender($resource[UserStructureTable::COLUMN_GENDER]);
		if(isset($resource[UserStructureTable::COLUMN_USERNAME])) $user->setUsername($resource[UserStructureTable::COLUMN_USERNAME]);
		if(isset($resource[UserStructureTable::COLUMN_PASSWORD])) $user->setPassword($resource[UserStructureTable::COLUMN_PASSWORD]);
		if(isset($resource[UserStructureTable::COLUMN_REGISTRATION_DATE])) $user->setRegistrationDate($resource[UserStructureTable::COLUMN_REGISTRATION_DATE]);
		
		return $user;
	}
	
}

?>