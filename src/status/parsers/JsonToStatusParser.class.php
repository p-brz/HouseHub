<?php
namespace househub\status\parsers;

use househub\status\StatusStructure;

class JsonToStatusParser{
	
	public function parse($statusName, $statusValue){
		$statusStructure = new StatusStructure();
		
		$statusStructure->setName($statusName);
		$statusStructure->setValue($statusValue);
		
		return $statusStructure;
	}
	
}