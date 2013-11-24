<?php
namespace househub\logs;

use househub\answer\AnswerEntity;

use lightninghowl\utils\sql\InsertQuery;

use househub\logs\tables\LogStructureTable;

use househub\access\DatabaseConnector;

class LogInsert{

	public function saveLog(AnswerEntity $answer, $userId, $method){
		$insert = new InsertQuery();
		$insert->setEntity(LogStructureTable::TABLE_NAME);

		$insert->setRowData(LogStructureTable::COLUMN_STATUS, $answer->getStatus());
		$insert->setRowData(LogStructureTable::COLUMN_USER_ID, $userId);
		$insert->setRowData(LogStructureTable::COLUMN_METHOD, $method);
		$insert->setRowData(LogStructureTable::COLUMN_MESSAGE, $answer->getMessage());

		$driver = DatabaseConnector::getDriver();
		$driver->exec($insert->getInstruction());
	}

}

?>