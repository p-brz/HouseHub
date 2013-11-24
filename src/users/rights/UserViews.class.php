<?php

namespace househub\users\rights;

use lightninghowl\utils\sql\SqlExpression;

use househub\objects\tables\ObjectStructureTable;

use househub\objects\ObjectStructure;

use househub\users\tables\UserViewPermissionsTable;

use lightninghowl\utils\sql\EntityFilter;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\SelectQuery;

use PDO;

class UserViews extends AbstractRights{
	
	public function __construct($userId, PDO $driver){
		$this->rights = array();

		$mainQuery = new SelectQuery();
		$mainQuery->addColumn(ObjectStructureTable::COLUMN_ID);
		$mainQuery->setEntity(ObjectStructureTable::TABLE_NAME);

		if($userId != 0){
			$query = new SelectQuery();
			$query->addColumn(UserViewPermissionsTable::COLUMN_OBJECT_ID);
			$query->setEntity(UserViewPermissionsTable::TABLE_NAME);

			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(UserViewPermissionsTable::COLUMN_USER_ID, '=', $userId));
			$query->setCriteria($criteria);	

			$exceptQuery = new SelectQuery();
			$exceptQuery->addColumn(UserViewPermissionsTable::COLUMN_ID);
			$exceptQuery->setEntity(UserViewPermissionsTable::TABLE_NAME);

			$permissionCriteria = new SqlCriteria();
			$permissionCriteria->add(new EntityFilter(ObjectStructureTable::COLUMN_ID, 'in(', $query->getInstruction().')'), SqlExpression::OR_OPERATOR);
			$permissionCriteria->add(new EntityFilter(ObjectStructureTable::COLUMN_ID, 'not in(', $exceptQuery->getInstruction().')'), SqlExpression::OR_OPERATOR);

			$mainCriteria = new SqlCriteria();
			$mainCriteria->add($permissionCriteria, SqlExpression::AND_OPERATOR);
			$mainCriteria->add(new SqlFilter(ObjectStructureTable::COLUMN_VALIDATED, '=', 1), SqlExpression::AND_OPERATOR);

			$mainQuery->setCriteria($mainCriteria);
		}
		
		$statement = $driver->query($mainQuery->getInstruction());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while($rs = $statement->fetch()){
			$this->rights[] = $rs[ObjectStructureTable::COLUMN_ID];
		}
	}
	
}

?>