<?php
namespace househub\status;

class StatusStructure{
	
	private $id;
	private $objectId;
	private $name;
	private $value;
	
        public function __construct($name="", $value="",$id=-1,$objectId=-1) {
            $this->id = $id;
            $this->objectId = $objectId;
            $this->name = $name;
            $this->value = $value;
        }
        
	public function getId() { return $this->id; } 
	public function getObjectId() { return $this->objectId; }
	public function getName() { return $this->name; } 
	public function getValue() { return $this->value; }
	 
	public function setId($x) { $this->id = $x; }
	public function setObjectId($x) { $this->objectId = $x; } 
	public function setName($x) { $this->name = $x; } 
	public function setValue($x) { $this->value = $x; } 

}