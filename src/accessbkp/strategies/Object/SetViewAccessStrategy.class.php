<?php

namespace Core\Access\Strategies\Object;

use Core\User\SessionManager;

use LightningHowl\Utils\Sql\UpdateQuery;

use LightningHowl\Utils\Sql\InsertQuery;

use LightningHowl\Utils\Sql\SqlExpression;

use LightningHowl\Utils\Sql\SqlFilter;

use LightningHowl\Utils\Sql\SqlCriteria;

use LightningHowl\Utils\Sql\SelectQuery;

use Core\User\Rights\UserViews;

use Core\Access\DatabaseConnector;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;

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
				
			}else if(!isset($parameters['name']) && !isset($parameters['pack'])){
				$answer->setMessage('@bad_parameters');
				
			}else{
				$objectId = intval($parameters['object']);
				
				$answer->setStatus(1);
				$answer->setMessage('@success');
				
				if(isset($parameters['name'])){
					$oper = $this->checkOper($userId, $objectId, $driver);
					$query = $this->setName($userId, $objectId, $parameters['name'], $oper);
					$driver->exec($query->getInstruction());
				}
				
				if(isset($parameters['pack'])){
					$oper = $this->checkOper($userId, $objectId, $driver);
					$query = $this->setPack($userId, $objectId, intval($parameters['pack']), $oper);
					$driver->exec($query->getInstruction());
				}
			}
		}
		
		return $answer;
		
	}
	
	private function setPack($userId, $objectId, $pack, $oper){
		$query = null;
		if($oper == 0){
			$query = new InsertQuery();
			$query->setEntity('objects_visuals');
			
			$query->setRowData('user_id', $userId);
			$query->setRowData('object_id', $objectId);
			$query->setRowData('iconpack', $pack);
			
			
		}else if($oper > 0){
			$query = new UpdateQuery();
			$query->setEntity('objects_visuals');
			
			$query->setRowData('iconpack', $pack);
			
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter('id', '=', $oper));
			$query->setCriteria($criteria);
		}
		
		return $query;
	}
	
	private function setName($userId, $objectId, $name, $oper){
		$query = null;
		if($oper == 0){
			$query = new InsertQuery();
			$query->setEntity('objects_visuals');
			
			$query->setRowData('user_id', $userId);
			$query->setRowData('object_id', $objectId);
			$query->setRowData('label', $name);
			
			
		}else if($oper > 0){
			$query = new UpdateQuery();
			$query->setEntity('objects_visuals');
			
			$query->setRowData('label', $name);
			
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter('id', '=', $oper));
			$query->setCriteria($criteria);
		}
		
		return $query;
	}
	
	
	private function checkOper($userId, $objectId, PDO $driver){
		$select = new SelectQuery();
		$select->addColumn('id');
		$select->setEntity('objects_visuals');
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter('user_id', '=', $userId), SqlExpression::AND_OPERATOR);
		$criteria->add(new SqlFilter('object_id', '=', $objectId), SqlExpression::AND_OPERATOR);
		$select->setCriteria($criteria);
		
		$statement = $driver->query($select->getInstruction());
		if($statement->rowCount() > 0){
			$rs = $statement->fetch(PDO::FETCH_ASSOC);
			$oper = intval($rs['id']);
		}else{
			$oper = 0;
		}
		
		return $oper;
	}
}

?>