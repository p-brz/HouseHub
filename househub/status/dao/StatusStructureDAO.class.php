<?php

namespace househub\status\dao;

use househub\status\builders\StatusStructureBuilder;
use househub\status\StatusStructure;
use househub\status\tables\StatusStructureTable;
use lightninghowl\utils\sql\DeleteQuery;
use lightninghowl\utils\sql\InsertQuery;
use lightninghowl\utils\sql\SelectQuery;
use lightninghowl\utils\sql\SqlCriteria;
use lightninghowl\utils\sql\SqlFilter;
use lightninghowl\utils\sql\UpdateQuery;
use PDO;
use RuntimeException;

class StatusStructureDAO {

    private $driver;

    public function __construct(PDO $driver) {
        $this->driver = $driver;
    }

    public function load($identifier) {
        if (!is_int($identifier)) {
            return null;
        }

        $select = new SelectQuery();
        $select->addColumn('*');
        $select->setEntity(StatusStructureTable::TABLE_NAME);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(StatusStructureTable::COLUMN_ID, '=', $identifier));
        $select->setCriteria($criteria);

        $statement = $this->driver->query($select->getInstruction());
        $builder = new StatusStructureBuilder();
        while ($rs = $statement->fetch(PDO::FETCH_ASSOC)) {
            $status = $builder->build($rs);
        }

        return $status;
    }

    public function listAll(SqlCriteria $criteria) {
        $statusArray = array();

        $select = new SelectQuery();
        $select->addColumn('*');
        $select->setEntity(StatusStructureTable::TABLE_NAME);
        $select->setCriteria($criteria);

        $statement = $this->driver->query($select->getInstruction());
        $builder = new StatusStructureBuilder();
        while ($rs = $statement->fetch(PDO::FETCH_ASSOC)) {
            $statusArray[] = $builder->build($rs);
        }

        return $statusArray;
    }

    public function insert(StatusStructure $status) {
        $insert = new InsertQuery();

        $insert->setEntity(StatusStructureTable::TABLE_NAME);
        $insert->setRowData(StatusStructureTable::COLUMN_NAME, $status->getName());
        $insert->setRowData(StatusStructureTable::COLUMN_OBJECT_ID, $status->getObjectId());
        $insert->setRowData(StatusStructureTable::COLUMN_VALUE, $status->getValue());

        $this->driver->exec($insert->getInstruction());

        return (int) $this->driver->lastInsertId();
    }

    public function update(StatusStructure $status, $strict = false) {
        $update = new UpdateQuery();

        $update->setEntity(StatusStructureTable::TABLE_NAME);
        $update->setRowData(StatusStructureTable::COLUMN_NAME, $status->getName());
        $update->setRowData(StatusStructureTable::COLUMN_OBJECT_ID, $status->getObjectId());
        $update->setRowData(StatusStructureTable::COLUMN_VALUE, $status->getValue());

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(StatusStructureTable::COLUMN_ID, '=', $status->getId()));
        $update->setCriteria($criteria);

        $updateCount = $this->driver->exec($update->getInstruction());
        if($updateCount != 1 && $strict){
            throw new RuntimeException("Cannot update the status " 
                    . $singleStatus . " of object with id " . $objectId);
        }
        
        return $updateCount;
    }

    public function delete(StatusStructure $status) {
        $delete = new DeleteQuery();

        $delete->setEntity(StatusStructureTable::TABLE_NAME);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(StatusStructureTable::COLUMN_ID, '=', $status->getId()));
        $delete->setCriteria($criteria);

        return $this->driver->exec($delete->getInstruction());
    }

}
