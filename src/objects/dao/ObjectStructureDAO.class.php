<?php
namespace househub\objects\dao;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\UpdateQuery;

use lightninghowl\utils\sql\InsertQuery;

use househub\objects\builders\ObjectStructureBuilder;

use lightninghowl\utils\sql\SqlFilter;

use househub\objects\tables\ObjectStructureTable;

use lightninghowl\utils\sql\SelectQuery;

use househub\objects\ObjectStructure;

use lightninghowl\utils\sql\SqlCriteria;

use PDO;

class ObjectStructureDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_numeric($identifier)){
			return null;
		}
		
		$objectStructure = null;
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(ObjectStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectStructureTable::COLUMN_ID, '=', intval($identifier)));
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new ObjectStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$objectStructure = $builder->build($rs);
		}
		
		return $objectStructure;
	}
	
	public function listAll(SqlCriteria $criteria){
		$objects = array();	
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(ObjectStructureTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new ObjectStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$objects[] = $builder->build($rs);
		}
		
		return $objects;
	}
	
	public function insert(ObjectStructure $structure){
		$insert = new InsertQuery();
		
		$insert->setEntity(ObjectStructureTable::TABLE_NAME);
		$insert->setRowData(ObjectStructureTable::COLUMN_TYPE, $structure->getType());
		$insert->setRowData(ObjectStructureTable::COLUMN_ADDRESS, $structure->getAddress());
		$insert->setRowData(ObjectStructureTable::COLUMN_SCHEME_NAME, $structure->getSchemeName());
		$insert->setRowData(ObjectStructureTable::COLUMN_PARENT_ID, $structure->getParentId());
		$insert->setRowData(ObjectStructureTable::COLUMN_PARENT_INDEX, $structure->getParentIndex());
		$insert->setRowData(ObjectStructureTable::COLUMN_VALIDATED, $structure->getValidated());
		$insert->setRowData(ObjectStructureTable::COLUMN_CONNECTED, $structure->getConnected());
		
		$this->driver->exec($insert->getInstruction());
		
		return $this->driver->lastInsertId();
	}
	
	public function update(ObjectStructure $structure){
		$update = new UpdateQuery();
		
		$update->setEntity(ObjectStructureTable::TABLE_NAME);
		$update->setRowData(ObjectStructureTable::COLUMN_TYPE, $structure->getType());
		$update->setRowData(ObjectStructureTable::COLUMN_ADDRESS, $structure->getAddress());
		$update->setRowData(ObjectStructureTable::COLUMN_SCHEME_NAME, $structure->getSchemeName());
		$update->setRowData(ObjectStructureTable::COLUMN_PARENT_ID, $structure->getParentId());
		$update->setRowData(ObjectStructureTable::COLUMN_PARENT_INDEX, $structure->getParentIndex());
		$update->setRowData(ObjectStructureTable::COLUMN_VALIDATED, $structure->getValidated());
		$update->setRowData(ObjectStructureTable::COLUMN_CONNECTED, $structure->getConnected());
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectStructureTable::COLUMN_ID, '=', $structure->getId()));
		$update->setCriteria($criteria);
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(ObjectStructure $structure){
		$delete = new DeleteQuery();
		
		$delete->setEntity(ObjectStructureTable::TABLE_NAME);	
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectStructureTable::COLUMN_ID, '=', $structure->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
}