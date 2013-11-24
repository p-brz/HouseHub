<?php

namespace househub\users\rights;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use househub\images\tables\ImageStructureTable;

use PDO;

class UserImages extends AbstractRights{
	
	public function __construct($userId, PDO $driver){
		$this->rights = array();
		$query = new SelectQuery();
		$query->addColumn(ImageStructureTable::COLUMN_ID);
		$query->setEntity(ImageStructureTable::TABLE_NAME);
		
		if($userId != 0){
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(ImageStructureTable::COLUMN_USER_ID, '=', $userId));
			$query->setCriteria($criteria);

			$backupQuery = new SelectQuery();
			$backupQuery->addColumn(ImageStructureTable::COLUMN_ID);
			$backupQuery->setEntity(ImageStructureTable::TABLE_NAME);
			
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(ImageStructureTable::COLUMN_USER_ID, '=', 0));
			$backupQuery->setCriteria($criteria);
			
			$statement = $driver->query($backupQuery->getInstruction());
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			while($rs = $statement->fetch()){
				$this->rights[] = $rs[ImageStructureTable::COLUMN_ID];
			}	
		}
		
		$statement = $driver->query($query->getInstruction());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while($rs = $statement->fetch()){
			$this->rights[] = $rs[ImageStructureTable::COLUMN_ID];
		}
		
	}
	
}

?>