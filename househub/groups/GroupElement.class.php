<?php

namespace  househub\groups;

class GroupElement{
	
	private $id;
	private $groupId;
	private $objectId;
	private $additionDate;
	
	public function getId() { return $this->id; } 
	public function getGroupId() { return $this->groupId; } 
	public function getObjectId() { return $this->objectId; } 
	public function getAdditionDate() { return $this->additionDate; } 
	
	public function setId($x) { $this->id = $x; } 
	public function setGroupId($x) { $this->groupId = $x; } 
	public function setObjectId($x) { $this->objectId = $x; } 
	public function setAdditionDate($x) { $this->additionDate = $x; } 
}

?>