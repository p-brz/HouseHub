<?php
namespace househub\access\strategies\supergroups;

use househub\access\strategies\AbstractAccessStrategy;

use househub\intents\parsers\IntentStructureJsonParser;

use househub\intents\tables\IntentStructureTable;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\intents\dao\IntentStructureDAO;

use househub\json\JsonArray;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

class GatherIntentsAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessMan = SessionManager::getInstance();
		$userId = $sessMan->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else if($userId != 0){
			$answer->setMessage('@forbidden');
		}else{
			
			$json = new JsonArray();
			
			$intentDAO = new IntentStructureDAO($driver);
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(IntentStructureTable::COLUMN_PARENT_ID, '=', 0));
			$intents = $intentDAO->listAll($criteria);
			
			$parser = new IntentStructureJsonParser();
			foreach($intents as $intent){
				$json->addElement($parser->parse($intent));
			}
			
			$answer->setStatus(1);
			$answer->setMessage('@success');
			$answer->setContent($json);
		}
		
		return $answer;
	}
	
}

?>