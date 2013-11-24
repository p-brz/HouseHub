<?php
namespace househub\intents;

class IntentStructure{
	
	private $id;
	private $type;
	private $address;
	private $schemeName;
	private $parentId;
	private $parentIndex;
	private $requestDate;
	
	public function getId() { return $this->id; } 
	public function getType() { return $this->type; } 
	public function getAddress() { return $this->address; }
	public function getSchemeName() { return $this->schemeName; } 
	public function getParentId() { return $this->parentId; } 
	public function getParentIndex() { return $this->parentIndex; } 
	public function getRequestDate() { return $this->requestDate; }
	 
	public function setId($x) { $this->id = $x; } 
	public function setType($x) { $this->type = $x; } 
	public function setAddress($x) { $this->address = $x; } 
	public function setSchemeName($x) { $this->schemeName = $x; } 
	public function setParentId($x) { $this->parentId = $x; } 
	public function setParentIndex($x) { $this->parentIndex = $x; } 
	public function setRequestDate($x) { $this->requestDate = $x; } 
}
?>