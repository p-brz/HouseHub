<?php
namespace househub\groups\home;

use lightninghowl\utils\StrOpers;

use househub\groups\dao\GroupElementDAO;

use househub\access\DatabaseConnector;

use househub\groups\GroupStructure;

use househub\objects\tables\ObjectStructureTable;

use househub\objects\home\HomeObjectManager;

use househub\groups\tables\GroupElementsTable;

use househub\groups\tables\GroupElements;

use lightninghowl\utils\sql\SelectQuery;

use lightninghowl\utils\sql\SqlExpression;

use househub\groups\dao\GroupVisualDAO;

use househub\groups\tables\GroupVisualTable;

use househub\groups\tables\GroupStructureTable;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\groups\dao\GroupStructureDAO;

use PDO;

class HomeGroupManager{
	
	public function loadGroup($identifier, $userId, PDO $driver){
		$group = new HomeGroup();
		$structure = $this->loadStructure($identifier, $driver);
		$group->setStructure($structure);
		
		if(is_null($group->getStructure())){
			return null;
		}
		$group->setVisual($this->loadVisual($identifier, $userId, $driver));
		$group->setObjects($this->loadObjects($identifier, $userId, $driver));
		
		return $group;
	}
	
	public function loadStructure($identifier, PDO $driver){
		if(!is_numeric($identifier)){
			return null;
			
		}
		
		$structureDAO = new GroupStructureDAO($driver);
		$structure = $structureDAO->load($identifier);
		return $structure;
	}
	
	public function loadVisual($identifier, $userId, PDO $driver){
		if(!is_numeric($identifier) || !is_numeric($userId)){
			return null;
		}
		
		$visual = null;
		$visualDAO = new GroupVisualDAO($driver);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_GROUP_ID, '=', $identifier), SqlExpression::AND_OPERATOR);
		
		$subCriteria = new SqlCriteria();
		$subCriteria->add(new SqlFilter(GroupVisualTable::COLUMN_USER_ID, '=', 0), SqlExpression::OR_OPERATOR);
		$subCriteria->add(new SqlFilter(GroupVisualTable::COLUMN_USER_ID, '=', $userId), SqlExpression::OR_OPERATOR);
		
		$criteria->add($subCriteria);
		$visuals = $visualDAO->listAll($criteria);
		
		foreach($visuals as $singleVisual){
			if($visual == null){
				$visual = $singleVisual;
			}else if($visual->getUserId() == 0 && $singleVisual->getUserId() > 0){
				$visual = $singleVisual;
			}
		}
		
		return $visual;
	}
	
	public function loadObjects($identifier, $userId, PDO $driver){
		$elements = array();
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupElementsTable::COLUMN_GROUP_ID, '=', $identifier));
		$groupElementDAO = new GroupElementDAO($driver);
		$elements = $groupElementDAO->listAll($criteria);
		
		return $elements;
	}
	
	public function saveGroup(){
		
	}
	
	public function updateGroup(){
		
	}
	
	public function deleteGroup(HomeGroup $group){
		$driver = DatabaseConnector::getDriver();
		$structureDAO = new GroupStructureDAO($driver);
		$structureDAO->delete($group->getStructure());
		
		$visualDAO = new GroupVisualDAO($driver);
		if(!is_null($group->getVisual())){
			$visualDAO->delete($group->getVisual());
		}
		
		$objects = $group->getObjects();
		$groupDAO = new GroupElementDAO($driver);
		foreach($objects as $object){
			$groupDAO->delete($object);
		}
	}
}

?>