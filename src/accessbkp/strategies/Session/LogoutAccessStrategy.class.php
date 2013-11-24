<?php

namespace Core\Access\Strategies\Session;

use Core\Answer\JsonAnswerParser;

use Core\User\SessionManager;

use Core\Access\Strategies\AbstractAccessStrategy;

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