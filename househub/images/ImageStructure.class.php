<?php

namespace househub\images;

class ImageStructure{
	
	private $id;
	private $userId;
	private $name;
	private $resource;
	private $registrationDate;
	
	public function getId() { return $this->id; }
	public function getUserId() { return $this->userId; }
	public function getName(){ return $this->name;  }
	public function getResource() { return $this->resource; }
	public function getRegistrationDate() { return $this->registrationDate; }
	
	public function setId($x) { $this->id = $x; }
	public function setUserId($x) { $this->userId = $x; }
	public function setName($x) { $this->name = $x; }
	public function setResource($x) { $this->resource = $x; }
	public function setRegistrationDate($x) { $this->registrationDate = $x; }
}

?>