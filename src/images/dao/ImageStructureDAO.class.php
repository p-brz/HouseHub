<?php
namespace househub\images\dao;

use househub\images\builders\ImageStructureBuilder;

use househub\images\ImageStructure;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\InsertQuery;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\images\tables\ImageStructureTable;

use lightninghowl\utils\sql\SelectQuery;

use PDO;

class ImageStructureDAO{
	
	private $dbDriver;
	
	public function __construct(PDO $driver){
		$this->dbDriver = $driver;
	}
	
	public function load($identifier){
		if(!is_numeric($identifier)){
			return null;
		}
		
		$image = null;
		
		$selectQuery = new SelectQuery();
		$selectQuery->addColumn('*');
		$selectQuery->setEntity(ImageStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ImageStructureTable::COLUMN_ID, '=', $identifier));
		$selectQuery->setCriteria($criteria);
		
		$statement = $this->dbDriver->query($selectQuery->getInstruction());
		
		$builder = new ImageStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$image = $builder->build($rs);
		}
		
		return $image;
	}
	
	public function listAll(SqlCriteria $criteria){
		$images = array();
		
		$selectQuery = new SelectQuery();
		$selectQuery->addColumn('*');
		$selectQuery->setEntity(ImageStructureTable::TABLE_NAME);
		$selectQuery->setCriteria($criteria);
		
		$statement = $this->dbDriver->query($selectQuery->getInstruction());
		
		$builder = new ImageStructureBuilder();
		while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
			$images[] = $builder->build($rs);
		}
	}
	
	public function insert(ImageStructure $image){
		$insertQuery = new InsertQuery();
		
		$insertQuery->setEntity(ImageStructureTable::TABLE_NAME);
		
		$insertQuery->setRowData(ImageStructureTable::COLUMN_IMAGE_NAME, $image->getName());
		$insertQuery->setRowData(ImageStructureTable::COLUMN_USER_ID, $image->getUserId());
		$insertQuery->setRowData(ImageStructureTable::COLUMN_RESOURCE, $image->getResource());
		
		return $this->dbDriver->exec($insertQuery->getInstruction());
	}
	
	public function delete(ImageStructure $image){
		$removeId = $image->getId();
		
		$deleteQuery = new DeleteQuery();
		$deleteQuery->setEntity(ImageStructureTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(ImageStructureTable::COLUMN_ID, '=', intval($removeId)));
		$deleteQuery->setCriteria($criteria);
		
		return $this->dbDriver->exec($deleteQuery->getInstruction());
	}
}

?>