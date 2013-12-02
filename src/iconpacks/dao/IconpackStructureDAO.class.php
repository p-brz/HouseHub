<?php

namespace househub\iconpacks\dao;

use househub\iconpacks\builders\IconpackStructureBuilder;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\UpdateQuery;

use househub\iconpacks\IconpackStructure;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use househub\iconpacks\tables\IconpackStructureTable;

use PDO;

class IconpackStructureDAO{
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
		if(!is_int($identifier) && !is_numeric($identifier)){
			return null;
		}
		$iconpack = null;
		
		$query = new SelectQuery();
		$query->addColumn('*');
		$query->setEntity(IconpackStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IconpackStructureTable::COLUMN_ID, '=', intval($identifier)));
		$query->setCriteria($criteria);
		
		$statement = $this->driver->query($query->getInstruction());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$builder = new IconpackStructureBuilder();
		
		while($rs = $statement->fetch()){
			$iconpack = $builder->build($rs);
		}
		
		return $iconpack;
	}
	
	public function listAll(SqlCriteria $criteria){
		$iconpacks = array();
		
		$query = new SelectQuery();
		$query->addColumn('*');
		$query->setEntity(IconpackStructureTable::TABLE_NAME);
		$query->setCriteria($criteria);
		
		$statement = $this->driver->query($query->getInstruction());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$builder = new IconpackStructureBuilder();
		
		while($rs = $statement->fetch()){
			$iconpacks[] = $builder->build($rs);
		}
		
		return $iconpacks;
	}
	
	public function insert(IconpackStructure $set){
		$insert = new InsertQuery();
		
		$insert->setEntity(IconpackStructureTable::TABLE_NAME);
		
		$insert->setRowData(IconpackStructureTable::COLUMN_NAME, $set->getName());
		$insert->setRowData(IconpackStructureTable::COLUMN_FOLDER, $set->getFolder());
		$insert->setRowData(IconpackStructureTable::COLUMN_TARGET, $set->getTarget());
		
		return $this->driver->exec($insert->getInstruction());
	}
	
	public function update(IconpackStructure $set){
		$update = new UpdateQuery();
		
		$update->setEntity(IconpackStructureTable::TABLE_NAME);
		
		$update->setRowData(IconpackStructureTable::COLUMN_NAME, $set->getName());
		$update->setRowData(IconpackStructureTable::COLUMN_FOLDER, $set->getFolder());
		$update->setRowData(IconpackStructureTable::COLUMN_TARGET, $set->getTarget());
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IconpackStructureTable::COLUMN_ID, '=', $set->getId()));
		$update->setCriteria($criteria);
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(IconpackStructure $set){
		$delete = new DeleteQuery();
		
		$delete->setEntity(IconpackStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(IconpackStructureTable::COLUMN_ID, '=', $set->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}

?>