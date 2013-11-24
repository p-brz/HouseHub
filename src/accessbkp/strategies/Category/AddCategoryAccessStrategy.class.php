<?php

namespace Core\Access\Strategies\Category;

use LightningHowl\Utils\Sql\MultiInsert;

use LightningHowl\Utils\Sql\InsertQuery;

use Core\User\Rights\UserViews;

use Core\User\SessionManager;

use Core\Access\DatabaseConnector;

use Core\Answer\JsonAnswerParser;

use Core\Answer\AnswerEntity;

use Core\Access\Strategies\AbstractAccessStrategy;

use PDO;

class AddCategoryAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = new AnswerEntity();
		
		
		$driver = DatabaseConnector::getDriver();
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			
			$permissions = new UserViews($userId, $driver);
			if(!isset($parameters['name']) || !isset($parameters['objects'])){
				$answer->setMessage('@add_category_bad_parameters');
			}else if(!is_array($parameters['objects'])){
				$answer->setMessage('@add_category_not_collection');
			}else{
				$insertQuery = new InsertQuery();
				$insertQuery->setEntity('uhb_categories');
				$insertQuery->setRowData('user_id', $userId);
				$insertQuery->setRowData('label', $parameters['name']);
				
				$count = $driver->exec($insertQuery->getInstruction());
				$categoryId = $driver->lastInsertId();
				
				foreach($parameters['objects'] as $objectId){
					if($permissions->verifyRights($objectId)){
						$query = $this->querifyObject($categoryId, $objectId);
						$driver->exec($query->getInstruction());	
					}
				}
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		return $answer;
	}
	
	
	private function querifyObject($categoryId, $objectId){
		$insertQuery = new InsertQuery();
		$insertQuery->setEntity('uhb_categories_rel');
		$insertQuery->setRowData('category_id', intval($categoryId));
		$insertQuery->setRowData('object_id', intval($objectId));
		
		return $insertQuery;
	}
} 

?>