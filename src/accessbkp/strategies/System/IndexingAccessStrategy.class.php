<?php

namespace Core\Access\Strategies\System;

use Core\Json\JsonObject;

use Core\Access\Strategies\AbstractAccessStrategy;

class IndexingAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		echo '<p>These are the system\'s methods indexes</p>';
		
		$indexing = parse_ini_file(dirname(__FILE__).'/../../strategyGuide.ini');
		foreach(array_keys($indexing) as $index){
			echo '<p><strong>'.$index.'</strong></p>';
		}
		
		return new JsonObject();
	}
	
}

?>