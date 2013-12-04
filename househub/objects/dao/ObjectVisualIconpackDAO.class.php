<?php
namespace househub\objects\dao;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\sql\UpdateQuery;

use househub\objects\ObjectVisualIconpack;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use househub\objects\builders\ObjectVisualIconpackBuilder;

use househub\objects\tables\ObjectVisualIconpackTable;

use PDO;

class ObjectVisualIconpackDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function load($identifier){
            if(!is_numeric($identifier)){
                    return null;
            }

            $iconpack = null;

            $select = new SelectQuery();
            $select->addColumn('*');
            $select->setEntity(ObjectVisualIconpackTable::TABLE_NAME);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(ObjectVisualIconpackTable::COLUMN_ID, '=', intval($identifier)));
            $select->setCriteria($criteria);

            $statement = $this->driver->query($select->getInstruction());
            $builder = new ObjectVisualIconpackBuilder();
            while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
                    $iconpack = $builder->build($rs);
            }

            return $iconpack;
	}
	
	public function listAll(SqlCriteria $criteria){
		$visuals = array();
		
		$select = new SelectQuery();
		$select->addColumn('*');
		$select->setEntity(ObjectVisualIconpackTable::TABLE_NAME);
		$select->setCriteria($criteria);
		
		$statement = $this->driver->query($select->getInstruction());
		$builder = new ObjectVisualIconpackBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$visuals[] = $builder->build($rs);
		}
		
		return $visuals;
	}
	
	public function insert(ObjectVisualIconpack $visual){
		$insert = new InsertQuery();
		
		$insert->setEntity(ObjectVisualIconpackTable::TABLE_NAME);
		$insert->setRowData(ObjectVisualIconpackTable::COLUMN_USER_ID, $visual->getUserId());
		$insert->setRowData(ObjectVisualIconpackTable::COLUMN_OBJECT_ID, $visual->getObjectId());
		$insert->setRowData(ObjectVisualIconpackTable::COLUMN_ICONPACK_ID, $visual->getIconpackId());
		
		$this->driver->exec($insert->getInstruction());
		
		return $this->driver->lastInsertId();
	}
	
	public function update(ObjectVisualIconpack $visual){
		$update = new UpdateQuery();
		
		$update->setEntity(ObjectVisualIconpackTable::TABLE_NAME);
		$update->setRowData(ObjectVisualIconpackTable::COLUMN_USER_ID, $visual->getUserId());
		$update->setRowData(ObjectVisualIconpackTable::COLUMN_OBJECT_ID, $visual->getObjectId());
		$update->setRowData(ObjectVisualIconpackTable::COLUMN_ICONPACK_ID, $visual->getIconpackId());
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectVisualIconpackTable::COLUMN_ID, '=', $visual->getId()));
		$update->setCriteria($criteria);
		
		return $this->driver->exec($update->getInstruction());
	}
	
	public function delete(ObjectVisualIconpack $visual){
		$delete = new DeleteQuery();
		
		$delete->setEntity(ObjectVisualIconpackTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ObjectVisualIconpackTable::COLUMN_ID, '=', $visual->getId()));
		$delete->setCriteria($criteria);
		
		return $this->driver->exec($delete->getInstruction());
	}
	
}

?>