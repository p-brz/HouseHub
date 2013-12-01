<?php
// COMPLETE!
namespace househub\access\strategies\object;

use househub\access\strategies\AbstractAccessStrategy;

use househub\objects\ObjectVisualIconpack;

use househub\objects\dao\ObjectVisualIconpackDAO;

use househub\objects\dao\ObjectVisualNameDAO;

use househub\objects\ObjectVisualName;

use househub\objects\ObjectVisual;

use househub\objects\home\HomeObjectManager;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use lightninghowl\utils\sql\UpdateQuery;

use lightninghowl\utils\sql\InsertQuery;

use househub\users\rights\UserViews;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use PDO;

class SetViewAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permissions = new UserViews($userId, $driver);
			
			if(!isset($parameters['object'])){
				$answer->setMessage('@bad_parameters');
			}else if(!$permissions->verifyRights(intval($parameters['object']))){
				$answer->setMessage('@forbidden');
				
			}else if(!isset($parameters['name']) && !isset($parameters['iconpack'])){
				$answer->setMessage('@bad_parameters');
				
			}else{
				$objectId = intval($parameters['object']);
				$manager = new HomeObjectManager();
				if(isset($parameters['name'])){
					$name = urldecode($parameters['name']);
					$objectName = $manager->loadVisualName($objectId, $userId, $driver);
					$objectNameDAO = new ObjectVisualNameDAO($driver);
					if(!is_null($objectName)){
						$objectName->setObjectName($name);
						$objectNameDAO->update($objectName);
					}else{
						$objectName = new ObjectVisualName();
						$objectName->setUserId($userId);
						$objectName->setObjectId($objectId);
						$objectName->setObjectName($name);
						
						$nameId = $objectNameDAO->insert($objectName);
						$objectName->setId($nameId);
					}
				}
				
				if(isset($parameters['iconpack'])){
					$iconpackId = $parameters['iconpack'];
					$objectIconpack = $manager->loadVisualIconpack($objectId, $userId, $driver);
					$objectIconpackDAO = new ObjectVisualIconpackDAO($driver);
					if(!is_null($objectIconpack)){
						$objectIconpack->setIconpackId($iconpackId);
						$objectIconpackDAO->update($visual);
					}else{
						$objectIconpack = new ObjectVisualIconpack();
						$objectIconpack->setUserId($userId);
						$objectIconpack->setObjectId($objectId);
						$objectIconpack->setIconpackId($iconpackId);
						
						$nameId = $objectIconpackDAO->insert($objectIconpack);
						$objectIconpack->setId($nameId);
					}
				}
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
			}
		}
		
		return $answer;
		
	}
}

?>