<?php

namespace Core\Access\Strategies\System;

use Core\Access\DatabaseConnector;

use LightningHowl\Utils\StrOpers;

use Core\Reader\SystemReader;

use Core\DAO\BlockStatusDAO;
use Core\DAO\BlockServiceDAO;
use Core\DAO\BlockObjectDAO;
use Exception;

/**
 * Erases the objects and logs
 * 
 * Estratégia de acesso para limpeza de dados gerais. Será removida no futuro por se tratar
 * de uma ferramenta perigosa
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.0.1
 */
class CleaningAccessStrategy implements AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$driver = DatabaseConnector::getDriver();
		
		$commands = '';
		$prefix = 'truncate table';
		
		if(isset($parameters['objects'])){
			$commands .= $prefix.' '.BlockObjectDAO::OBJECT_TABLE.';';
			$commands .= $prefix.' '.BlockServiceDAO::SERVICE_TABLE.';';
			$commands .= $prefix.' '.BlockStatusDAO::STATUS_TABLE.';';
		}
		
		if(isset($parameters['logs'])){
			$commands .= $prefix.' uhb_logs;';
		}
		
		if(!empty($commands)){
			$driver->exec($commands);			
		}
	}
	
}

?>