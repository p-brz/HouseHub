<?php

namespace Core\Access\Strategies\Object;

use Core\Access\Strategies\AbstractAccessStrategy;

use Core\Answer\AnswerEntity;

use Core\DAO\BlockObjectDAO;

use Core\Access\DatabaseConnector;

use LightningHowl\Utils\StrOpers;

use Core\Reader\SystemReader;

use Core\Loaders\ObjectSaver;
use Core\Parsers\JsonToObjectParser;
use LightningHowl\Utils\Url\UrlHandler;
use LightningHowl\Database\DbDriver;
use LightningHowl\Database\Arguments\MySQLArgument;
use LightningHowl\Reader\JsonReader;

/**
 * Register an object on the system
 * 
 * Utilizado para registrar um dado objeto no sistema
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 */
class ConnectionAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = new AnswerEntity();
		$answer->setStatus(0);
		$answer->setMessage('@error');
		
		
		$driver = DatabaseConnector::getDriver();
		
		$addr = isset($parameters['addr']) ? $parameters['addr'] : $_SERVER['REMOTE_ADDR'];
		
		$handler = new UrlHandler('http://'.$addr);
		$handler->run();
		
		$objJson = $handler->getContent();
		
		$parser = new JsonToObjectParser();
		$obj = $parser->jsonToObject($objJson, 'http://'.$addr);
		
		if($obj->getId() == 0){
			$saver = new ObjectSaver();
			$saver->save($obj, $driver);
		}else{
			$objDAO = new BlockObjectDAO($driver);
			$object = $objDAO->getBlockObject($obj->getId());
			$object->setConnected(1);
			$objDAO->update($object);
		}
		
	}
	
	private function submitIds(BlockObject $object){
		$handler = new UrlHandler($object->getAddr().'configs/id', 'POST');
		$handler->addField('', $fieldValue);
		$handler->run();
	}
	
}

?>