<?php

namespace househub\access\strategies\session;

use househub\json\JsonData;

use househub\users\session\SessionManager;

use househub\access\strategies\AbstractAccessStrategy;

class VerifyLoginAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$answer->setStatus(1);
			$answer->setMessage('@success');
			
			$rights = $userId > 0 ? 1 : 0;
			$answer->addContent(new JsonData("user_rights", $rights));
		}
		
		return $answer;
		
	}
	
}

?>