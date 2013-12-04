<?php

namespace househub\groups\dao;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\sql\SelectQuery;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\UpdateQuery;

use househub\groups\builders\GroupVisualBuilder;

use lightninghowl\utils\sql\SqlFilter;

use househub\groups\tables\GroupVisualTable;

use lightninghowl\utils\sql\SqlCriteria;

use househub\groups\GroupVisual;

use PDO;

class GroupVisualDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_int($identifier)){
			return null;
		}
		
		$visual = null;
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(GroupVisualTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_ID, '=', $identifier));
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new GroupVisualBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$visual = $builder->build($rs);
		}
		
		return $visual;
	}
	
	public function listAll(SqlCriteria $criteria){
		$visuals = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(GroupVisualTable::TABLE_NAME);
		
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new GroupVisualBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$visuals[] = $builder->build($rs);
		}
		
		return $visuals;
	}
	
	public function insert(GroupVisual $visual){
		$insert = new InsertQuery();
		
		$insert->setEntity(GroupVisualTable::TABLE_NAME);
		
		$insert->setRowData(GroupVisualTable::COLUMN_USER_ID, $visual->getUserId());
		$insert->setRowData(GroupVisualTable::COLUMN_GROUP_ID, $visual->getGroupId());
		$insert->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $visual->getGroupName());
		$insert->setRowData(GroupVisualTable::COLUMN_GROUP_IMAGE_ID, $visual->getGroupImageId());
		
		$this->driver->exec($insert->getInstruction());
		return $this->driver->lastInsertId();
	}
	
	public function update(GroupVisual $visual){
		$update = new UpdateQuery();
		$update->setEntity(GroupVisualTable::TABLE_NAME);
		
		$update->setRowData(GroupVisualTable::COLUMN_USER_ID, $visual->getUserId());
		$update->setRowData(GroupVisualTable::COLUMN_GROUP_ID, $visual->getGroupId());
		$update->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $visual->getGroupName());
		$update->setRowData(GroupVisualTable::COLUMN_GROUP_IMAGE_ID, $visual->getGroupImageId());
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(GroupVisual $visual){
		$delete = new DeleteQuery();
		
		$delete->setEntity(GroupVisualTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_ID, '=', $visual->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}

?>