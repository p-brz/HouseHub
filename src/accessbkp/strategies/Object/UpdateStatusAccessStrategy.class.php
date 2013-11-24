<?php

namespace Core\Access\Strategies\Object;

use Core\Loaders\ObjectLoader;

use Core\DAO\BlockStatusDAO;

use Core\Access\DatabaseConnector;

use Core\DAO\BlockObjectDAO;

use Core\Parsers\JsonToObjectParser;

use LightningHowl\Utils\Url\UrlHandler;

use Core\Access\Strategies\AbstractAccessStrategy;

class UpdateStatusAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$index = $parameters['index'];
		$request = $_SERVER['REMOTE_ADDR'];
		
		$addr = 'http://'.$request;
		if(intval($index) > -1){
			$addr .= '/objects/'.$index;
		}
		
		$handler = new UrlHandler($addr);
		$handler->run();
		
		$objJson = $handler->getContent();
		if(!is_null($objJson)){
			$wildObject = json_decode($objJson, true);
			$objId = $wildObject['configs']['id'];
			$objStatus = $wildObject['status'];
			$driver = DatabaseConnector::getDriver();
			
			$objLoader = new ObjectLoader();
			$realObject = $objLoader->load($objId, $driver);
			$realStatus = $realObject->getBlockStatus();
			
			$stsDao = new BlockStatusDAO($driver);
			foreach($realStatus as $key=>$singleStatus){
				$name = $singleStatus->getName();
				$value = $objStatus[$name];
				if(intval($value) != intval($singleStatus->getValue())){
					$singleStatus->setValue($value);
					$stsDao->update($singleStatus);
				}
			}
		}			
	}
	
}

?>