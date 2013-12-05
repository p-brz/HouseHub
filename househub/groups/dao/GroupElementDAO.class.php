<?php

namespace househub\groups\dao;

use househub\groups\builders\GroupElementBuilder;
use househub\groups\GroupElement;
use househub\groups\tables\GroupElementsTable;
use lightninghowl\utils\sql\DeleteQuery;
use lightninghowl\utils\sql\InsertQuery;
use lightninghowl\utils\sql\SelectQuery;
use lightninghowl\utils\sql\SqlCriteria;
use lightninghowl\utils\sql\SqlFilter;
use lightninghowl\utils\sql\UpdateQuery;
use PDO;

class GroupElementDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function listAll(SqlCriteria $criteria){
		$elements = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(GroupElementsTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new GroupElementBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$elements[] = $builder->build($rs);
		}
		
		return $elements;
	}
	
	public function insert(GroupElement $element){
		$insert = new InsertQuery();
		
		$insert->setEntity(GroupElementsTable::TABLE_NAME);
		
		$insert->setRowData(GroupElementsTable::COLUMN_GROUP_ID, $element->getGroupId());
		$insert->setRowData(GroupElementsTable::COLUMN_ELEMENT_ID, $element->getObjectId());
		
		$this->driver->exec($insert->getInstruction());
		return $this->driver->lastInsertId();
	}
	public function update(GroupElement $element){
		$update = new UpdateQuery();
                
		$update->setEntity(GroupElementsTable::TABLE_NAME);
		
		$update->setRowData(GroupElementsTable::COLUMN_GROUP_ID, $element->getGroupId());
		$update->setRowData(GroupElementsTable::COLUMN_ELEMENT_ID, $element->getObjectId());
		
                $criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupElementsTable::COLUMN_ID, '=', $element->getId()));
		$update->setCriteria($criteria);
                
		$this->driver->exec($update->getInstruction());
		return $this->driver->lastInsertId();
	}
	
	public function delete(GroupElement $element){
		$delete = new DeleteQuery();
		$delete->setEntity(GroupElementsTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupElementsTable::COLUMN_ID, '=', $element->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}