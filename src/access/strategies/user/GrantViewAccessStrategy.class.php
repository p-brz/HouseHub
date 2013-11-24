<?php
// Complete
namespace Core\Access\Strategies\User;

use LightningHowl\Utils\Sql\InsertQuery;

use Core\User\SessionManager;

use Core\Access\DatabaseConnector;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

class GrantViewAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
			
		}else if($userId != 0){
			$answer->setMessage('@forbidden');
			
		}else if(!isset($parameters['user']) || !isset($parameters['objects'])){
			$answer->setMessage('@bad_parameters');
			
		}else if(!is_int($parameters['user']) || !is_array($parameters['objects']) ){
			$answer->setMessage('@bad_types');
			
		}else{
			$grantUserId = $parameters['user'];
			$noError = true;
			$queries = array();
			foreach($parameters['objects'] as $objectId){
				if(!is_int($objectId)){
					$answer->setMessage('@bad_types');
					$noError = false;
					break;
				}else{
					$insertQuery = new InsertQuery();
					$insertQuery->setEntity('uhb_user_views');
					$insertQuery->setRowData('user_id', $grantUserId);
					$insertQuery->setRowData('object_id', $objectId);
					
					$queries[] = $insertQuery;
				}
			}
			
			if($noError){
				foreach ($queries as $query){
					$driver->exec($query->getInstruction());
				}
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		$this->parseAnswer($answer, $parser);
	}
	
}

?>