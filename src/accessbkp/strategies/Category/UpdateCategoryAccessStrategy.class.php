<?php

namespace Core\Access\Strategies\Category;

use LightningHowl\Utils\Sql\DeleteQuery;

use Core\User\Rights\UserCategories;

use LightningHowl\Utils\Sql\InsertQuery;

use LightningHowl\Utils\Sql\SqlExpression;

use LightningHowl\Utils\Sql\SelectQuery;

use LightningHowl\Utils\Sql\SqlFilter;

use LightningHowl\Utils\Sql\SqlCriteria;

use LightningHowl\Utils\Sql\UpdateQuery;

use Core\Loaders\CategoryLoader;

use Core\User\SessionManager;

use Core\Access\DatabaseConnector;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

class UpdateCategoryAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permission = new UserCategories($userId, $driver);
			
			if(!isset($parameters['category'])){
				$answer->setMessage('@bad_parameters');
			}else{
				
				$categoryId = intval($parameters['category']);
				$loader = new CategoryLoader();
				$category = $loader->load($categoryId, $driver);
				
				if(is_null($category)){
					$answer->setMessage('category_not_found');
				}else{
					$isRoot = $category->getUserId() == 0;
					$isAdmin = $userId == 0;
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
					
					if(isset($parameters['name'])){
						
						$query = null;
						if($isRoot && $isAdmin){
							$query = $this->changeDefaultName($categoryId, $parameters['name']);
							
						}else if($isRoot && !$isAdmin){
							$query = $this->changeName($categoryId, $parameters['name']);
							
						}else if(!$isRoot && $isAdmin){
							$answer->setStatus(0);
							$answer->setMessage('@personal_data');
							$query = null;
						}else{
							if(!$permission->verifyRights($categoryId)){
								$answer->setStatus(0);
								$answer->setMessage('@forbidden');
								$query = null;
							}else{
								$query = $this->changeDefaultName($categoryId, $parameters['name']);	
							}
						}
						
						if(is_null($query)){
							$answer->setStatus(0);
							$answer->setMessage('@update_category_error');
						}else{
							$driver->exec($query->getInstruction());
						}
					}
					
					if(isset($parameters['objects'])){
						if(!$permission->verifyRights($categoryId) || ($category->getUserId() == 0 && $userId != 0)){
							$answer->setStatus(0);
							$answer->setMessage('@forbidden');
							$query = null;
						}else if(is_array($parameters['objects'])){
							$answer->setStatus(0);
							$answer->setMessage('@category_not_collection');
						}else{
							$delete = new DeleteQuery();
							$delete->setEntity('uhb_categories_rel');
							
							$criteria = new SqlCriteria();
							$criteria->add(new SqlFilter('category_id', '=', $categoryId));
							$delete->setCriteria($criteria);
							
							$driver->exec($delete->getInstruction());
							
							foreach($parameters['objects'] as $objectId){
								if($permissions->verifyRights($objectId)){
									$query = $this->querifyObject($categoryId, $objectId);
									$driver->exec($query->getInstruction());	
								}
							}
						}
						
						return $answer;
						
					}
				}
				
			}
		}
	}
	
	// Change the category default name
	private function changeDefaultName($categoryId, $newName){
		$update = new UpdateQuery();
		$update->setEntity('uhb_categories');
		$update->setRowData('name', $newName);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter('id', '=', $newName));
		$update->setCriteria($criteria);
		
		return $update;
	}
	
	// Change the category label
	private function changeName($categoryId, $newName, $userId, PDO $driver){
		$select = new SelectQuery();
		$select->addColumn('id');
		$select->setEntity('uhb_categories_visuals');
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter('user_id', '=', $userId), SqlExpression::AND_OPERATOR);
		$criteria->add(new SqlFilter('category_id', '=', $categoryId), SqlExpression::AND_OPERATOR);
		$select->setCriteria($criteria);
		
		$statement = $driver->query($select->getInstruction());
		if($statement->rowCount() > 0){
			while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
				$id = $rs['id'];
			}
			
			// Update
			$update = new UpdateQuery();
			$update->setEntity('uhb_categories_visuals');
			$update->setRowData('category_name', $newName);
			
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter('id', '=', $id));
			$update->setCriteria($criteria);
			
			return $update;
			
		}else{
			// Insert
			$insert = new InsertQuery();
			$insert->setEntity('uhb_categories_visuals');
			
			$insert->setRowData('user_id', $userId);
			$insert->setRowData('category_id', $categoryId);
			$insert->setRowData('category_name', $newName);
			
			return $insert;
		}
		
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