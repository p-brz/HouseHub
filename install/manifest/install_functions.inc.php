<?php

function declareSystem(&$jsonManifest, $directory, $packageName){
	$classArray = array();
	if(is_dir($directory)){
		$scanning = absoluteDirectory(scandir($directory));
		
		foreach($scanning as $scan){
			$evaluablePath = $directory.DIRECTORY_SEPARATOR.$scan;
			if(is_dir($evaluablePath)){
				if(empty($packageName)){
					$package = $scan;
				}else{
					$package = $packageName.'\\'.$scan;
				}
				$classArray[$scan] = declareSystem($jsonManifest, $evaluablePath, $package);
				
			}else if(is_file($evaluablePath)){
				if(strEndsWith($scan, '.class.php')){
					$pureLength = strlen($scan) - strlen('.class.php');
					$className = substr($scan, 0, $pureLength);
					$jsonManifest[$packageName.'\\'.$className] = $evaluablePath;
					if(is_array($classArray)){
						$classArray[] = $className;
					}
				}
			}
		}
	}
	
	return $classArray;
}

function absoluteDirectory($array){
	return array_diff($array, array('.', '..'));
}

function strEndsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function launchClassManifest($contents, $output){
	$manifestLocation = $output.DIRECTORY_SEPARATOR.'classManifest.json';
	file_put_contents($manifestLocation, $contents);
}

function dumpClasses($result, $spaceString = ''){
	if(is_array($result)){
		echo '<p>';
		foreach($result as $key=>$entity){
			
			if(is_string($key)) echo $spaceString.'* '.$key;//.'<br/>';
			dumpClasses($entity, $spaceString.'&nbsp;&nbsp;');		
			
		}
		echo '</p>';
	}else if(is_string($result)){
		echo $spaceString.'&nbsp;&nbsp;+ '.$result.'<br/>';
	}
}

?>