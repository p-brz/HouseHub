<?php
namespace househub\objects;

class ObjectVisualIconpack{
	
	private $id;
	private $userId;
	private $objectId;
	private $IconpackId;
	private $setDate;
	
	public function getId() { return $this->id; } 
	public function getUserId() { return $this->userId; } 
	public function getObjectId() { return $this->objectId; } 
	public function getIconpackId() { return $this->IconpackId; } 
	public function getSetDate() { return $this->setDate; } 
	
	public function setId($x) { $this->id = $x; } 
	public function setUserId($x) { $this->userId = $x; } 
	public function setObjectId($x) { $this->objectId = $x; } 
	public function setIconpackId($x) { $this->IconpackId = $x; } 
	public function setSetDate($x) { $this->setDate = $x; } 
}

?>