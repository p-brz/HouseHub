<?php
namespace househub\access;

/**
 * Launches the application based on the parameters
 * 
 * Esta classe utiliza o documento de texto 'strategyGuide.ini'
 * para alternar entre as possíveis estratégias de ação disponíveis
 * ao núcleo.
 * 
 * @author Alison Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0.0
 */

use househub\json\JsonData;

use househub\answer\JsonAnswerParser;

class Launcher{
	
	/**
	 * Launches the application
	 * 
	 * @param array $parameters Geralmente uma das globais do PHP ($_GET, por exemplo)
	 */
	public function launch($parameters){
		$guide = parse_ini_file(dirname(__FILE__).DIRECTORY_SEPARATOR.'strategyGuide.ini');
		$method = isset($parameters['method']) ? $parameters['method'] : 'cougar';
		$class = isset($guide[$method]) ? $guide[$method] : $guide['cougar'];
		$_DESTINY = __NAMESPACE__.'\\strategies\\'.$class;
		
		$destiny = new $_DESTINY;
		
		$answer = $destiny->requestAccess($parameters);
		$parser = new JsonAnswerParser();
		
		$json = $this->parseAnswer($answer, $parser);
		$json->addElement(new JsonData("method", $method));
		echo $json->valueToString();
	}
	
	private function parseAnswer($answer, $parser){
		return $parser->answerToJson($answer);
	}
}

?>