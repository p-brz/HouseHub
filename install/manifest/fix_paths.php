<?php
error_reporting(E_ALL); 
ini_set('display_errors', '1');
require_once(dirname(__FILE__).'/install_functions.inc.php');
require_once(dirname(__FILE__).'/configurations.php');

// Loading all classes definitions
function launch(){
	$manifestDetails = manifestDetails();
	$projectRoot = $manifestDetails['root'];
	$output = $manifestDetails['output'];
	$codePacks = $manifestDetails['codePacks'];
	
	$manifest = array();
	$classes = array();
	foreach($codePacks as $codePack){
		$dir = $codePack['directory'];
		$codeBlocks = $codePack['blocks'];
		foreach($codeBlocks as $codeBlock){
			if(!empty($codeBlock['input'])){
				$input = $projectRoot.DIRECTORY_SEPARATOR.$dir;
			}else{
				$input = $projectRoot.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$codeBlock['input'];
			}
			$namespace = $codeBlock['parent'];
			
			$classes[] = declareSystem($manifest, $input, $namespace);
		}
	}
	
	$manifestContent = json_encode($manifest);
	launchClassManifest($manifestContent, $output);
	
	return $classes;
}

?>