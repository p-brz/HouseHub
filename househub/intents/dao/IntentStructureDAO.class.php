<?php
namespace househub\intents\dao;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SelectQuery;

use househub\intents\builders\IntentStructureBuilder;

use househub\intents\tables\IntentStructureTable;

use househub\intents\IntentStructure;

use lightninghowl\utils\sql\SqlCriteria;

use PDO;

class IntentStructureDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_numeric($identifier)){
			return null;
		}
		
		$intentStructure = null;
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(IntentStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IntentStructureTable::COLUMN_ID, '=', intval($identifier)));
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new IntentStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$intentStructure = $builder->build($rs);
		}
		
		return $intentStructure;
	}
	
	public function listAll(SqlCriteria $criteria = null){
		
		$intents = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(IntentStructureTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new IntentStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$intents[] = $builder->build($rs);
		}
		
		return $intents;
	}
	
	public function insert(IntentStructure $structure){
		$insert = new InsertQuery();
		
		$insert->setEntity(IntentStructureTable::TABLE_NAME);
		$insert->setRowData(IntentStructureTable::COLUMN_TYPE, $structure->getType());
		$insert->setRowData(IntentStructureTable::COLUMN_ADDRESS, $structure->getAddress());
		$insert->setRowData(IntentStructureTable::COLUMN_SCHEME_NAME, $structure->getSchemeName());
		$insert->setRowData(IntentStructureTable::COLUMN_PARENT_ID, $structure->getParentId());
		$insert->setRowData(IntentStructureTable::COLUMN_PARENT_INDEX, $structure->getParentIndex());
		
		$this->driver->exec($insert->getInstruction());
		
		return $this->driver->lastInsertId();
	}
	
	public function delete(IntentStructure $structure){
		$delete = new DeleteQuery();
		
		$delete->setEntity(IntentStructureTable::TABLE_NAME);	
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IntentStructureTable::COLUMN_ID, '=', $structure->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}