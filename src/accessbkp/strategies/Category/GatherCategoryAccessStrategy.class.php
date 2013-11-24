<?php

namespace Core\Access\Strategies\Category;

use Core\Access\Strategies\AbstractAccessStrategy;

use Core\User\SessionManager;

use Core\User\Rights\UserCategories;

use Core\Access\DatabaseConnector;

use Core\Parsers\CategoryJsonParser;

use Core\Loaders\CategoryLoader;

use Core\Answer\JsonAnswerParser;

use Core\Answer\AnswerEntity;

class GatherCategoryAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$sessManager->startSession();
		$userId = $sessManager->getSessionVariable('user_id');
		
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permission = new UserCategories($userId, $driver);
			
			if(!isset($parameters['category'])){
				$answer->setMessage('@category_bad_parameters');
			}else{
				$categoryId = $parameters['category'];
				$driver = DatabaseConnector::getDriver();
				
				$loader = new CategoryLoader();
				$category = $loader->load($categoryId, $driver);
				
				if(is_null($category)){
					$answer->setMessage('@category_not_found');		
				}else if(!$permission->verifyRights($categoryId)){
					$answer->setMessage('@category_forbidden');
				}else{
					$catParser = new CategoryJsonParser();
					$categoryJson = $catParser->categoryToJson($category, $userId);
					$categoryJson->setName("category");
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
					$answer->addContent($categoryJson);
				}
			}
		}
		
		return $answer;
		
	}
	
}

?>