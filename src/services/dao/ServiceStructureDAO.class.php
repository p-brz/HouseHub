<?php
namespace househub\services\dao;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\UpdateQuery;

use lightninghowl\utils\sql\InsertQuery;

use househub\services\builders\ServiceStructureBuilder;

use lightninghowl\utils\sql\SqlFilter;

use househub\services\tables\ServiceStructureTable;

use lightninghowl\utils\sql\SelectQuery;

use househub\services\ServiceStructure;

use lightninghowl\utils\sql\SqlCriteria;

use PDO;

class ServiceStructureDAO{
	
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
            $select->setEntity(ServiceStructureTable::TABLE_NAME);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(ServiceStructureTable::COLUMN_ID, '=', $identifier));
            $select->setCriteria($criteria);

            $statement = $this->driver->query($select->getInstruction());
            $builder = new ServiceStructureBuilder();
            while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
                    $service = $builder->build($rs);
            }

            return $service;
	}
	
	public function listAll(SqlCriteria $criteria){
		$services = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(ServiceStructureTable::TABLE_NAME);
		
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new ServiceStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$services[] = $builder->build($rs);
		}
		
		return $services;
	}
	
	public function insert(ServiceStructure $service){
		$insert = new InsertQuery();
		
		$insert->setEntity(ServiceStructureTable::TABLE_NAME);
		$insert->setRowData(ServiceStructureTable::COLUMN_NAME, $service->getName());
		$insert->setRowData(ServiceStructureTable::COLUMN_OBJECT_ID, $service->getObjectId());
		
		$this->driver->exec($insert->getInstruction());
		
		return (int)$this->driver->lastInsertId();
	}
	
	public function update(ServiceStructure $service){
		$update = new UpdateQuery();
		
		$update->setEntity(ServiceStructureTable::TABLE_NAME);
		$update->setRowData(ServiceStructureTable::COLUMN_NAME, $service->getName());
		$update->setRowData(ServiceStructureTable::COLUMN_OBJECT_ID, $service->getObjectId());
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ServiceStructureTable::COLUMN_ID, '=', $service->getId()));
		$update->setCriteria($criteria);
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(ServiceStructure $service){
		$delete = new DeleteQuery();
		
		$delete->setEntity(ServiceStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ServiceStructureTable::COLUMN_ID, '=', $service->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}

?>