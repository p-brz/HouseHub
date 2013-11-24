<?php

namespace househub\iconpacks;

class IconpackStructure{
	
	private $id;
	private $name;
	private $folder;
	private $target;
	private $entryDate;
	private $dictionary;
	
	public function getId() { return $this->id; }
	public function getName() { return $this->name; } 
	public function getFolder() { return $this->folder; } 
	public function getTarget() { return $this->target; } 
	public function getEntryDate() { return $this->entryDate; }
	public function getDictionary() { return $this->dictionary; } 
	
	public function setId($x) { $this->id = $x; }
	public function setName($x) { $this->name = $x; } 
	public function setFolder($x) { $this->folder = $x; } 
	public function setTarget($x) { $this->target = $x; } 
	public function setEntryDate($x) { $this->entryDate = $x; }
	public function setDictionary($x) { $this->dictionary = $x; }
}

?>