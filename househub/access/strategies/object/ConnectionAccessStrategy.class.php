<?php
// COMPLETE!
namespace househub\access\strategies\object;

/**
 * Register an object on the system
 * 
 * Utilizado para registrar um dado objeto no sistema
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 */
use lightninghowl\utils\StrOpers;

use househub\objects\dao\ObjectStructureDAO;

use househub\objects\home\HomeObjectManager;

use househub\intents\parsers\JsonToIntentParser;

use househub\intents\home\HomeIntentManager;

use househub\intents\parsers\IntentSaver;

use househub\access\DatabaseConnector;

use lightninghowl\utils\url\UrlHandler;

use househub\access\strategies\AbstractAccessStrategy;

class ConnectionAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$addr = $_SERVER['REMOTE_ADDR'];
		
		$handler = new UrlHandler('http://'.$addr);
		$handler->run();
		
		$objJson = $handler->getContent();
		$objAssocArray = json_decode($objJson, true);
		
		if(is_array($objAssocArray)){
			$objId = isset($objAssocArray['configs']['id']) ? $objAssocArray['configs']['id'] : null ;
			
			if($objId == 0){
				$intentParser = new JsonToIntentParser();
				$intent = $intentParser->parse($objAssocArray, $addr);
				
				$intentSaver = new HomeIntentManager();
				$intentSaver->saveIntent($intent, $driver);
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}else if($objId > 0){
				$manager = new HomeObjectManager();
				$structureDAO = new ObjectStructureDAO($driver);
				$this->updateConnection($objId, $manager, $structureDAO, $driver);
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		return $answer;
	}
	
	private function updateConnection($objId, $manager, $structureDAO, $driver){
		$structure = $manager->loadStructure($objId, $driver);
		
		if(!is_null($structure)){
			$structure->setConnected(1);
			$structureDAO->update($structure);
			
			$subObjects = $manager->loadSubObjects($objId, $driver);
			if(!empty($subObjects) && !is_null($subObjects)) {
				foreach($subObjects as $subObject){
					$objectId = $subObject->getStructure()->getId();
					$this->updateConnection($objectId, $manager, $structureDAO, $driver);	
				}
			}
		}
	}
	
}

?>