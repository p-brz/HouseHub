<?php
namespace househub\services\parsers;

use househub\services\ServiceStructure;

class JsonToServiceParser{
	
	public function parse($serviceName){
		$serviceStructure = new ServiceStructure();
		$serviceStructure->setName($serviceName);
		
		return $serviceStructure;
	}
	
}

