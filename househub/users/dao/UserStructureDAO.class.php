<?php
namespace househub\users\dao;

use lightninghowl\utils\encrypting\Sha1Hash;

use househub\users\tables\UserStructureTable;

use lightninghowl\utils\sql\InsertQuery;

use househub\users\UserStructure;

use PDO;

class UserStructureDAO{
	
	private $driver;
	
	public function __construct(PDO $driver){
		$this->driver = $driver;
	}
	
	public function insert(UserStructure $user){
            $insert = new InsertQuery();

            $insert->setEntity(UserStructureTable::TABLE_NAME);
            $insert->setRowData(UserStructureTable::COLUMN_NAME, $user->getName());
            $insert->setRowData(UserStructureTable::COLUMN_NICKNAME, $user->getNickname());
            $insert->setRowData(UserStructureTable::COLUMN_GENDER, $user->getGender());
            $insert->setRowData(UserStructureTable::COLUMN_USERNAME, $user->getUsername());

            $encoder = new Sha1Hash();
            $password = $encoder->encrypt($user->getPassword());
            $insert->setRowData(UserStructureTable::COLUMN_PASSWORD, $password);

            $this->driver->exec($insert->getInstruction());

            return $this->driver->lastInsertId();
	}
}
?>