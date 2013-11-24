<?php
// COMPLETE
namespace househub\access\strategies\groups;

use househub\users\rights\UserImages;

use lightninghowl\utils\sql\DeleteQuery;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\groups\GroupVisual;

use lightninghowl\utils\sql\UpdateQuery;

use lightninghowl\utils\sql\SelectQuery;

use househub\groups\tables\GroupVisualTable;

use househub\groups\GroupElement;

use househub\groups\dao\GroupElementDAO;

use househub\users\rights\UserViews;

use househub\groups\tables\GroupElementsTable;

use househub\groups\home\HomeGroupManager;

use househub\users\rights\UserGroups;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use househub\access\strategies\AbstractAccessStrategy;

class UpdateGroupAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permission = new UserGroups($userId, $driver);
			
			if(!isset($parameters['group'])){
				$answer->setMessage('@bad_parameters');
			}else{
				
				$groupId = intval($parameters['group']);
				$manager = new HomeGroupManager();
				$group = $manager->loadGroup($groupId, $userId, $driver);
				
				if(is_null($group)){
					$answer->setMessage('group_not_found');
				}else{
					// Se o grupo foi criado pelo administrador
					$isAdminOwner = $group->getUserId() == 0;
					
					// Se o usuário logado é administrador
					$isAdmin = $userId == 0;
					
					$answer->setStatus(1);
					$answer->setMessage('@success');
					
					// Se queremos mudar o nome desse grupo
					if(isset($parameters['group_name'])){
						
						$query = null;
						if($isAdminOwner && $isAdmin){
							// Se o grupo pertence ao administrador e o usuário é administrador
							// RESULTADO: Mudamos diretamente
							$query = $this->changeDefaultName($groupId, $parameters['group_name']);
							
						}else if($isAdminOwner && !$isAdmin){
							// Se o grupo é um cômodo e o usuário é comum
							// RESULTADO: Mudamos apenas para o perfil do user
							$query = $this->changeName($groupId, $parameters['group_name']);
							
						}else if(!$isAdminOwner && $isAdmin){
							// Se o grupo é comum e o usuário é administrador
							// RESULTADO: Impedimos essa mudança
							$answer->setStatus(0);
							$answer->setMessage('@personal_data');
							$query = null;
							
						}else if(!$isAdminOwner && !$isAdmin){
							// Se o grupo é local
							if(!$permission->verifyRights($groupId)){
								$answer->setStatus(0);
								$answer->setMessage('@forbidden');
								$query = null;
							}else{
								$query = $this->changeDefaultName($groupId, $parameters['group_name']);	
							}
						}
						
						if(is_null($query)){
							$answer->setStatus(0);
							$answer->setMessage('@update_group_error');
						}else{
							$driver->exec($query->getInstruction());
						}
					}
					
					if(isset($parameters['group_image'])){
						$imageId = $parameters['group_image'];
						$imagePermissions = new UserImages($userId, $driver);
						if(!is_numeric($imageId)){
							$answer->setStatus(0);
							$answer->setMessage('@not_image');
						}else if(!$imagePermissions->verifyRights($imageId)){
							$answer->setStatus(0);
							$answer->setMessage('@forbidden');
						}else{
							
						}
					}
					
					// Caso desejemos modificar os objetos que pertencem a esse grupo
					if(isset($parameters['objects'])){
						
						if(!$permission->verifyRights($groupId) || ($group->getUserId() == 0 && $userId != 0)){
							// Se o usuário não tem permissão de edição ou o grupo é um cômodo
							$answer->setStatus(0);
							$answer->setMessage('@forbidden');
							$query = null;
						}else if(!is_array($parameters['objects'])){
							// Se 'objects' não é um array
							$answer->setStatus(0);
							$answer->setMessage('@group_not_collection');
						}else{
							// Se está tudo bem e podemos alterar os objetos
							
							// Removendo os itens anteriores
							$delete = new DeleteQuery();
							$delete->setEntity(GroupElementsTable::TABLE_NAME);
							
							$criteria = new SqlCriteria();
							$criteria->add(new SqlFilter('group_id', '=', $groupId));
							$delete->setCriteria($criteria);
							
							$driver->exec($delete->getInstruction());
							
							// Adicionando os novos itens
							$permissions = new UserViews($userId, $driver);
							$elementDAO = new GroupElementDAO($driver);
							foreach($parameters['objects'] as $objectId){
								if($permissions->verifyRights($objectId)){
									$element = new GroupElement();
									$element->setGroupId($groupId);
									$element->setObjectId($objectId);

									$elementDAO->insert($element);
								}
							}
						}
						
						
					}
				}
				
			}
		}
		return $answer;
	}
	
	// Change the group default name
	private function changeDefaultName($groupId, $newName){
		$update = new UpdateQuery();
		$update->setEntity(GroupVisualTable::TABLE_NAME);
		$update->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $newName);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_GROUP_ID, '=', $groupId));
		$update->setCriteria($criteria);
		
		return $update;
	}
	
	// Change the group label
	private function changeName($groupId, $newName, $userId, PDO $driver){
		$select = new SelectQuery();
		$select->addColumn(GroupVisualTable::COLUMN_ID);
		$select->setEntity(GroupVisualTable::TABLE_NAME);
		
		$criteria = new SqlCriteria();
		$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_USER_ID, '=', $userId), SqlExpression::AND_OPERATOR);
		$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_GROUP_ID, '=', $groupId), SqlExpression::AND_OPERATOR);
		$select->setCriteria($criteria);
		
		$statement = $driver->query($select->getInstruction());
		if($statement->rowCount() > 0){
			while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
				$id = $rs['id'];
			}
			
			// Update
			$update = new UpdateQuery();
			$update->setEntity(GroupVisualTable::TABLE_NAME);
			$update->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $newName);
			
			$criteria = new SqlCriteria();
			$criteria->add(new SqlFilter(GroupVisualTable::COLUMN_ID, '=', $id));
			$update->setCriteria($criteria);
			
			return $update;
			
		}else{
			// Insert
			$insert = new InsertQuery();
			$insert->setEntity(GroupVisualTable::TABLE_NAME);
			
			$insert->setRowData(GroupVisualTable::COLUMN_USER_ID, $userId);
			$insert->setRowData(GroupVisualTable::COLUMN_GROUP_ID, $groupId);
			$insert->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $newName);
			
			return $insert;
		}
		
	}
	
}

?>