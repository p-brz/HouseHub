<?php

namespace househub\groups\dao;

use lightninghowl\utils\StrOpers;

use househub\groups\tables\GroupStructureTable;

use househub\groups\builders\GroupStructureBuilder;

use househub\groups\GroupStructure;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\UpdateQuery;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SelectQuery;

use lightninghowl\utils\sql\SqlCriteria;

use PDO;

class GroupStructureDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_numeric($identifier)){
			return null;
		}
		
		$group = null;
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(GroupStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupStructureTable::COLUMN_ID, '=', $identifier));
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new GroupStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$group = $builder->build($rs);
		}
		
		return $group;
	}
	
	public function listAll(SqlCriteria $criteria){
		$groups = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(GroupStructureTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new GroupStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$groups[] = $builder->build($rs);
		}
		
		return $groups;
	}
	
	public function insert(GroupStructure $structure){
		$insert = new InsertQuery();
		$insert->setEntity(GroupStructureTable::TABLE_NAME);
		$insert->setRowData(GroupStructureTable::COLUMN_USER_ID, $structure->getUserId());
		
		$this->driver->exec($insert->getInstruction());
		
		return $this->driver->lastInsertId();
	}
	
	public function update(GroupStructure $structure){
		$update = new UpdateQuery();
		$update->setEntity(GroupStructureTable::TABLE_NAME);
		$update->setRowData(GroupStructureTable::COLUMN_USER_ID, $structure->getUserId());
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupStructureTable::COLUMN_ID, '=', $structure->getId()));
		$update->setCriteria($criteria);
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(GroupStructure $structure){
		$delete = new DeleteQuery();
		$delete->setEntity(GroupStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupStructureTable::COLUMN_ID, '=', $structure->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}

?>