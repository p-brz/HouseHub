<?php
namespace househub\intents\home;

class HomeIntent{
	
	private $structure;
	private $subIntents;
	
	public function getStructure() { return $this->structure; } 
	public function getSubIntents() { return $this->subIntents; }
	 
	public function setStructure($x) { $this->structure = $x; } 
	public function setSubIntents($x) { $this->subIntents = $x; }
}


?>