<?php
namespace househub\status\dao;

use lightninghowl\utils\sql\InsertQuery;

use househub\status\builders\StatusStructureBuilder;

use lightninghowl\utils\sql\SqlFilter;

use househub\status\tables\StatusStructureTable;

use househub\status\StatusStructure;

use lightninghowl\utils\sql\SqlCriteria;

use PDO;

class StatusStructureDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_int($identifier)){
			return null;
		}
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(StatusStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ServiceStructureTable::COLUMN_ID, '=', $identifier));
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new StatusStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$status = $builder->build($rs);
		}
		
		return $status;
	}
	
	public function listAll(SqlCriteria $criteria){
		$statusArray = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(StatusStructureTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new StatusStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$statusArray[] = $builder->build($rs);
		}
		
		return $statusArray;
	}
	
	public function insert(StatusStructure $status){
		$insert = new InsertQuery();
		
		$insert->setEntity(StatusStructureTable::TABLE_NAME);
		$insert->setRowData(StatusStructureTable::COLUMN_NAME, $status->getName());
		$insert->setRowData(StatusStructureTable::COLUMN_OBJECT_ID, $status->getObjectId());
		$insert->setRowData(StatusStructureTable::COLUMN_VALUE, $status->getValue());
		
		$this->driver->exec($insert->getInstruction());
		
		return $this->driver->lastInsertId();
	}
	
	public function update(StatusStructure $status){
		
	}
	
	public function delete(StatusStructure $status){
		
	}
}

?>