<?php

namespace househub\users\rights;


use lightninghowl\utils\sql\SqlExpression;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\groups\tables\GroupStructureTable;

use lightninghowl\utils\sql\SelectQuery;

use PDO;

class UserGroups extends AbstractRights{
	
	public function __construct($userId, PDO $driver){
		$this->rights = array();
		$query = new SelectQuery();
		$query->addColumn(GroupStructureTable::COLUMN_ID);
		$query->setEntity(GroupStructureTable::TABLE_NAME);
		
		if($userId != 0){
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(GroupStructureTable::COLUMN_USER_ID, '=', $userId), SqlExpression::OR_OPERATOR);
			$criteria->add(new SqlFilter(GroupStructureTable::COLUMN_USER_ID, '=', 0), SqlExpression::OR_OPERATOR);
			$query->setCriteria($criteria);
		}
		
		$statement = $driver->query($query->getInstruction());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while($rs = $statement->fetch()){
			$this->rights[] = $rs[GroupStructureTable::COLUMN_ID];
		}
		
	}
	
}

?>