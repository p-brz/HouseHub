<?php

namespace househub\access;

use lightninghowl\utils\StrOpers;

use lightninghowl\database\DbDriver;

use lightninghowl\database\arguments\MySQLArgument;

use lightninghowl\reader\JsonReader;

use househub\readers\SystemReader;

final class DatabaseConnector{
	
	private function __construct(){}
	
	public static function getDriver(){
            $sysRes = SystemReader::getInstance();
            $path = $sysRes->translate(SystemReader::INDEX_ROOTPATH).'/'.$sysRes->translate(SystemReader::INDEX_DATABASE).'/'.$sysRes->translate(SystemReader::INDEX_DBCONF);
            $path = StrOpers::strFixPath($path);

//		$reader = new JsonReader($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$path);
            $reader = new JsonReader($path);
            $argument = new MySQLArgument();

            $argument->setHost($reader->get('db_host'));
            $argument->setPort($reader->get('db_port'));
            $argument->setDbName($reader->get('db_name'));
            $argument->setDbUser($reader->get('db_user'));
            $argument->setDbPass($reader->get('db_pass'));

            $driver = DbDriver::open($argument);

            return $driver;
	} 
}

?>