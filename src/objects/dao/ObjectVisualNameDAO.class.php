<?php
namespace househub\objects\dao;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\UpdateQuery;

use househub\objects\ObjectVisualName;

use lightninghowl\utils\sql\InsertQuery;

use househub\objects\tables\ObjectVisualNameTable;

use househub\objects\builders\ObjectVisualNameBuilder;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use PDO;

class ObjectVisualNameDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_numeric($identifier)){
			return null;
		}
		
		$objectName = null;
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(ObjectVisualNameTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectVisualNameTable::COLUMN_ID, '=', intval($identifier)));
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new ObjectVisualNameBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$objectName = $builder->build($rs);
		}
		
		return $objectName;
	}
	
	public function listAll(SqlCriteria $criteria){
		$visuals = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(ObjectVisualNameTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new ObjectVisualNameBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$visuals[] = $builder->build($rs);
		}
		
		return $visuals;
	}
	
	public function insert(ObjectVisualName $visual){
		$insert = new InsertQuery();
		
		$insert->setEntity(ObjectVisualNameTable::TABLE_NAME);
		$insert->setRowData(ObjectVisualNameTable::COLUMN_USER_ID, $visual->getUserId());
		$insert->setRowData(ObjectVisualNameTable::COLUMN_OBJECT_ID, $visual->getObjectId());
		$insert->setRowData(ObjectVisualNameTable::COLUMN_OBJECT_NAME, $visual->getObjectName());
		
		$this->driver->exec($insert->getInstruction());
		
		return $this->driver->lastInsertId();
	}
	
	public function update(ObjectVisualName $visual){
		$update = new UpdateQuery();
		
		$update->setEntity(ObjectVisualNameTable::TABLE_NAME);
		$update->setRowData(ObjectVisualNameTable::COLUMN_USER_ID, $visual->getUserId());
		$update->setRowData(ObjectVisualNameTable::COLUMN_OBJECT_ID, $visual->getObjectId());
		$update->setRowData(ObjectVisualNameTable::COLUMN_OBJECT_NAME, $visual->getObjectName());
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectVisualNameTable::COLUMN_ID, '=', $visual->getId()));
		$update->setCriteria($criteria);
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(ObjectVisualName $visual){
		$delete = new DeleteQuery();
		
		$delete->setEntity(ObjectVisualNameTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectVisualNameTable::COLUMN_ID, '=', $visual->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}