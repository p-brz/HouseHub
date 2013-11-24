<?php
namespace househub\objects;

class ObjectVisualName{
	
	private $id;
	private $userId;
	private $objectId;
	private $objectName;
	private $setDate;
	
	public function getId() { return $this->id; } 
	public function getUserId() { return $this->userId; } 
	public function getObjectId() { return $this->objectId; } 
	public function getObjectName() { return $this->objectName; } 
	public function getSetDate() { return $this->setDate; } 

	public function setId($x) { $this->id = $x; } 
	public function setUserId($x) { $this->userId = $x; } 
	public function setObjectId($x) { $this->objectId = $x; } 
	public function setObjectName($x) { $this->objectName = $x; } 
	public function setSetDate($x) { $this->setDate = $x; } 
}

?>