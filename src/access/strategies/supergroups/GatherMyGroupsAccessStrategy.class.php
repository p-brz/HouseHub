<?php

namespace househub\access\strategies\supergroups;

use househub\json\JsonArray;

use househub\groups\home\HomeGroupJsonParser;

use househub\groups\home\HomeGroupManager;

use lightninghowl\utils\sql\SqlFilter;

use househub\access\DatabaseConnector;

use househub\users\session\SessionManager;

use lightninghowl\utils\sql\SqlCriteria;

use househub\groups\tables\GroupStructureTable;

use lightninghowl\utils\sql\SelectQuery;

use househub\access\strategies\AbstractAccessStrategy;

use PDO;

class GatherMyGroupsAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessMan = SessionManager::getInstance();
		$userId = $sessMan->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			
			$select = new SelectQuery();
			$select->addColumn(GroupStructureTable::COLUMN_ID);
			$select->setEntity(GroupStructureTable::TABLE_NAME);
			
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(GroupStructureTable::COLUMN_USER_ID, '=', $userId));
			$select->setCriteria($criteria);
			
			$json = new JsonArray();
			$statement = $driver->query($select->getInstruction());
			$manager = new HomeGroupManager();
			$parser = new HomeGroupJsonParser();
			while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
				$homeGroup = $manager->loadGroup($rs[GroupStructureTable::COLUMN_ID], $userId, $driver);
				$jsonGroup = $parser->parse($homeGroup);
				
				$json->addElement($jsonGroup);
			}
			
			$answer->setStatus(1);
			$answer->setMessage('@success');
			$answer->setContent($json);
		}
		
		return $answer;
	}
	
}

?>