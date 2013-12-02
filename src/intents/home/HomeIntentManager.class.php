<?php
namespace househub\intents\home;

use lightninghowl\utils\sql\SqlExpression;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use househub\intents\tables\IntentStructureTable;

use househub\intents\IntentStructure;

use househub\intents\dao\IntentStructureDAO;

use PDO;

class HomeIntentManager{
	
	public function loadIntent($identifier, PDO $driver){
		$intent = new HomeIntent();
		
		$intent->setStructure($this->loadStructure($identifier, $driver));
		$intent->setSubIntents($this->loadSubIntents($identifier, $driver));
		
		return $intent;
	}
	
	public function loadStructure($identifier, PDO $driver){
		if(!is_numeric($identifier)){
			return null;
		}
		
		$dao = new IntentStructureDAO($driver);
		$structure = $dao->load($identifier);
		
		return $structure;
	}
	
	public function loadSubIntents($identifier, PDO $driver){
		$intents = array();
		
		$select = new SelectQuery();
		$select->addColumn(IntentStructureTable::COLUMN_ID);
		$select->setEntity(IntentStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IntentStructureTable::COLUMN_PARENT_ID, '=', $identifier));
		$select->setCriteria($criteria);
		
		$statement = $driver->query($select->getInstruction());
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$intents[] = $this->loadIntent(intval($rs[IntentStructureTable::COLUMN_ID]), $driver);
		}
		
		return $intents;
	}
	
	public function saveIntent(HomeIntent $homeIntent, PDO $driver){
		$intent = $homeIntent;
		$structure = $intent->getStructure();
		
		$intentDAO = new IntentStructureDAO($driver);
		$intentId = $intentDAO->insert($structure);
		$structure->setId($intentId);
		
		$subIntents = $intent->getSubIntents();
		foreach($subIntents as $key=>$localIntent){
			$localStructure = $localIntent->getStructure();
			$localStructure->setParentId($intentId);
			$localIntent->setStructure($localStructure);
			
			$insertStructure = $this->saveIntent($localIntent, $driver);
			$subIntents[$key] = $insertStructure;
		}
		
		$intent->setStructure($structure);
		$intent->setSubIntents($subIntents);
		
		return $intent;
	}
	
	public function deleteIntent(HomeIntent $intent, PDO $driver){
		$delete = new DeleteQuery();
		$intentId = $intent->getStructure()->getId();
		
		$delete->setEntity(IntentStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IntentStructureTable::COLUMN_ID, '=', $intentId), SqlExpression::OR_OPERATOR);
		$criteria->add(new SqlFilter(IntentStructureTable::COLUMN_PARENT_ID, '=', $intentId), SqlExpression::OR_OPERATOR);
		$delete->setCriteria($criteria);
		
		return $driver->exec($delete->getInstruction());
	}
}

?>