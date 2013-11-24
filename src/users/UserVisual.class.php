<?php
namespace househub\users;


class UserVisual{
	
	private $id;
	private $imageId;
	private $setDate;
	
	public function getId() { return $this->id; } 
	public function getImageId() { return $this->imageId; } 
	public function getSetDate() { return $this->setDate; }
	 
	public function setId($x) { $this->id = $x; } 
	public function setImageId($x) { $this->imageId = $x; } 
	public function setSetDate($x) { $this->setDate = $x; } 
	
}
?>