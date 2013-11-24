<?php

namespace househub\access;

final class DatabaseConnector{
	
	
	private function __construct(){}
	
	public static function getDriver(){
		$sysRes = SystemReader::getInstance();
		$path = $sysRes->translate(SystemReader::INDEX_ROOTPATH).'/'.$sysRes->translate(SystemReader::INDEX_DATABASE).'/'.$sysRes->translate(SystemReader::INDEX_DBCONF);
		$path = StrOpers::strFixPath($path);
		
		$reader = new JsonReader($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$path);
		$argument = new MySQLArgument();
		$argument->setReader($reader);
		$driver = DbDriver::open($argument);
		
		return $driver;
	} 
}

?>