<?php

namespace Core\Access\Strategies\Category;

use LightningHowl\Utils\StrOpers;

use Core\Loaders\CategoryLoader;

use LightningHowl\Utils\Sql\SqlFilter;

use LightningHowl\Utils\Sql\SqlCriteria;

use LightningHowl\Utils\Sql\DeleteQuery;

use Core\User\Rights\UserViews;

use Core\Access\DatabaseConnector;

use Core\User\Rights\UserCategories;

use Core\User\SessionManager;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

use PDO;

class RemoveCategoryAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$categories = new UserCategories($userId, $driver);
			
			if(!isset($parameters['category'])){
				$answer->setMessage('@bad_parameters');
				
			}else{
				$categoryId = intval($parameters['category']);
				$loader = new CategoryLoader();
				$category = $loader->load($categoryId, $driver);
				if(is_null($category)){
					$answer->setMessage('@category_not_found');
					
				}else if(!$categories->verifyRights($parameters['category'])){
					$answer->setMessage('@forbidden');
				
				}else if($category->getUserId() == 0 && $userId != 0){
					$answer->setMessage('@forbidden');
					
				}else{
					$delete = new DeleteQuery();
					$delete->setEntity('uhb_categories');
					$criteria = new SqlCriteria();
					$criteria->add(new SqlFilter('id', '=', $categoryId));
					
					$delete->setCriteria($criteria);
					$driver->exec($delete->getInstruction());
					
					$relDelete = new DeleteQuery();
					$relDelete->setEntity('uhb_categories_rel');
					$criteria = new SqlCriteria();
					$criteria->add(new SqlFilter('category_id', '=', $categoryId));
					
					$relDelete->setCriteria($criteria);
					$driver->exec($relDelete->getInstruction());
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
				}
				
			}
		}
		
		return $answer;
		
	}
	
}

?>