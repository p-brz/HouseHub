<?php

namespace househub\access\strategies\session;

use househub\access\strategies\AbstractAccessStrategy;

use househub\users\session\SessionManager;

class LogoutAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		$sessManager = SessionManager::getInstance();
		$sessManager->destroySession();
		
		$answer->setStatus(1);
		$answer->setMessage('@success');
		
		return $answer;
		
	}
	
}

?>