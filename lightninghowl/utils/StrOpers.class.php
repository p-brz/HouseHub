<?php

namespace lightninghowl\utils;

final class StrOpers{
	
	private function __construct(){}
	
	public static function varFancyDump($var){
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}
	
	public static function strEndsWith($string, $target){
		return $string === "" || substr($string, -strlen($target)) === $target;
	}
	
	public static function strStartsWith($string, $target){
		return $string === "" || substr($string, 0, strlen($target)) === $target;
	}
	
	public static function strFixPath($path){
		$retVal = $path;
		
		$retVal = str_replace('\\', DIRECTORY_SEPARATOR, $retVal);
		$retVal = str_replace('/', DIRECTORY_SEPARATOR, $retVal);
		
		return $retVal;
	}
	
	public static function strWebPath($path){
		$retVal = $path;
		
		$retVal = str_replace('\\', DIRECTORY_SEPARATOR, $retVal);
		
		return $retVal;
	}
}

?>