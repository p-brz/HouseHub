<?php 

namespace househub\readers;

use Exception;

final class SystemReader extends Dictionary{
	const INDEX_ROOTPATH = 'projectRoot';
	const INDEX_SCHEMES = 'schemes_path';
	const INDEX_CONFIGS = 'configs_path';
	const INDEX_DATABASE = 'database_path';
	const INDEX_LANGUAGES = 'language_path';
	const INDEX_PACKS	= 'iconpacks_path';
	const INDEX_UPLOADS = 'uploads_path';
	
	const INDEX_SYSLANG = 'system_language';
	const INDEX_DBCONF = 'database_file';
	
	private function __construct(){
		$systemRoot = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
		$filename = $systemRoot.'hub.ini';
		$setup = $systemRoot.'setup.ini';
		if(is_file($filename) && is_file($setup)){
			$source = parse_ini_file($filename);
			$settings = parse_ini_file($setup);
			
			$sysRes = array_merge($source, $settings);
			
			$this->setSource($sysRes);
		}else{

			throw new Exception('File "hub.ini" or "setup.ini" not found. Please reinstall your software.');
		}
	}
	
	// Singleton
	private static $instance;
	
	public static function getInstance($forceRebuild = false){
		if(!self::$instance || $forceRebuild){
			self::$instance = new SystemReader();
		}
		
		return self::$instance;
	}
	
}

?>