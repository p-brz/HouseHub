<?php

echo 'Worked!';
		
echo '<p>Application set-up complete!</p>';

echo '<p>Administrator has been registered</p>';

//deleteDir(dirname(__FILE__).DIRECTORY_SEPARATOR.'install');

function deleteDir($dirPath) {
    if(is_dir($dirPath)){
    	foreach(scandir($dirPath) as $directory){
    		if($directory == '.' || $directory == '..') continue;
    		deleteDir($dirPath.'/'.$directory);
    	}
    	
    	rmdir($dirPath);
    }else if(is_file($dirPath)){
    	echo unlink($dirPath) ? 'deleted' : 'removed';
    	echo '<br/>';
    }
}
?>