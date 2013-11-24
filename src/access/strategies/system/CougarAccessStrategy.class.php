<?php
namespace househub\access\strategies\system;

use househub\access\strategies\AbstractAccessStrategy;

class CougarAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$answer->setMessage('@empty');
		
		return $answer;
	}
	
}