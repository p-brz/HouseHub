<?php
namespace househub\services;

class ServiceStructure{
	
	private $id;
	private $objectId;
	private $name;
	
	public function getId() { return $this->id; }
	public function getObjectId() { return $this->objectId; } 
	public function getName() { return $this->name; } 
	
	public function setId($x) { $this->id = $x; } 
	public function setObjectId($x) { $this->objectId = $x; }
	public function setName($x) { $this->name = $x; }
	
}

?>