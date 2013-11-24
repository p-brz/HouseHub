<?php
// Complete
namespace househub\access\strategies\object;

use househub\objects\home\HomeObjectManager;

use househub\access\DatabaseConnector;

use lightninghowl\utils\url\UrlHandler;

use househub\access\strategies\AbstractAccessStrategy;

// Atenção: Preciso modificar essa classe para validar o endereço de atualização
class UpdateStatusAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		// First, the submitted index
		$index = $parameters['obj_index'];
		$request = $_SERVER['REMOTE_ADDR'];
		
		// Let us fix the address
		$addr = 'http://'.$request;
		if(intval($index) > -1){
			$addr .= '/objects/'.$index;
		}
		
		// Call the page
		$handler = new UrlHandler($addr);
		$handler->run();
		
		// The json answer
		$objJson = $handler->getContent();
		if(!is_null($objJson)){
			
			$wildObject = json_decode($objJson, true);
			$objId = $wildObject['configs']['id'];
			$objStatus = $wildObject['status'];
			$driver = DatabaseConnector::getDriver();
			
			$objManager = new HomeObjectManager();
			$realStatus = $objManager->loadStatus($identifier, $driver);
			
			if(!is_null($realStatus) && !empty($realStatus)){
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
	
}

?>