<?php
namespace househub\access;

use househub\json\JsonData;

use househub\answer\JsonAnswerParser;

use househub\logs\LogInsert;

use househub\users\session\SessionManager;

class Launcher{
	
	public function launch($parameters){
		$guide = parse_ini_file(dirname(__FILE__).DIRECTORY_SEPARATOR.'strategyGuide.ini');
		$method = isset($parameters['method']) ? $parameters['method'] : 'cougar';
		$class = isset($guide[$method]) ? $guide[$method] : $guide['cougar'];
		$_DESTINY = __NAMESPACE__.'\\strategies\\'.$class;
		
		$destiny = new $_DESTINY;
		
		$answer = $destiny->requestAccess($parameters);

		$logSaver = new LogInsert();
		$sessMan = SessionManager::getInstance();
		$userId = $sessMan->getSessionVariable('user_id');
		if(is_null($userId)){
			$userId = -1;
		}

		$logSaver->saveLog($answer, $userId, $method);

		$parser = new JsonAnswerParser();
		$json = $this->parseAnswer($answer, $parser);
		$json->addElement(new JsonData("method", $method));
		echo $json->valueToString();
                
                return $answer;
	}
	
	private function parseAnswer($answer, $parser){
		return $parser->answerToJson($answer);
	}
	
}

?>