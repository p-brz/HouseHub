<?php

// COMPLETE

namespace househub\access\strategies\groups;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\groups\dao\GroupElementDAO;
use househub\groups\GroupElement;
use househub\groups\GroupVisual;
use househub\groups\home\HomeGroup;
use househub\groups\home\HomeGroupManager;
use househub\groups\tables\GroupElementsTable;
use househub\groups\tables\GroupVisualTable;
use househub\users\rights\UserGroups;
use househub\users\rights\UserViews;
use househub\users\session\SessionManager;
use lightninghowl\utils\sql\DeleteQuery;
use lightninghowl\utils\sql\InsertQuery;
use lightninghowl\utils\sql\SelectQuery;
use lightninghowl\utils\sql\SqlCriteria;
use lightninghowl\utils\sql\SqlExpression;
use lightninghowl\utils\sql\SqlFilter;
use lightninghowl\utils\sql\UpdateQuery;
use PDO;

class UpdateGroupAccessStrategy extends AbstractAccessStrategy {

    const GROUP_ARG = 'group';
    const GROUPNAME_ARG = 'group_name';
    const GROUPIMG_ARG = 'group_image';
    const OBJECTS_ARG = 'objects';

    private $dbDriver;

    public function __construct($driver = null) {
        $this->dbDriver = (!is_null($driver) ? $driver : DatabaseConnector::getDriver());
    }

    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();
        $driver = $this->dbDriver;
        $userId = $this->getUserId();
        if ($this->checkParameters($parameters, $userId, $answer)) {
            $this->updateGroup($parameters, $userId, $answer, $driver);
        }
        return $answer;
    }

    private function getUserId() {
        $sessManager = SessionManager::getInstance();
        $userId = $sessManager->getSessionVariable('user_id');

        return $userId;
    }

    private function checkParameters($parameters, $userId, $answer) {
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
            return false;
        } else if (!isset($parameters[self::GROUP_ARG])) {
            $answer->setMessage('@bad_parameters');
            return false;
        }
        return true;
    }

    private function updateGroup($parameters, $userId, $answer, $driver) {
        $groupId = intval($parameters[self::GROUP_ARG]);
        $manager = new HomeGroupManager();
        $group = $manager->loadGroup($groupId, $userId, $driver);

        if (is_null($group)) {
            $answer->setMessage('@group_not_found');
        } else {
            // Se o grupo foi criado pelo administrador
            $isAdminOwner = $group->getUserId() == 0;
            // Se o usuário logado é administrador
            $isAdmin = $userId == 0;

            $answer->setStatus(1);
            $answer->setMessage('@success');

            // Se queremos mudar o nome desse grupo
            if (isset($parameters[self::GROUPNAME_ARG])) {
                if (is_null($group->getVisual())) {
                    $groupVisual = new GroupVisual();
                    $groupVisual->setUserId($userId);
                    $groupVisual->setGroupId($groupId);
                    $groupVisual->setGroupName($parameters[self::GROUPNAME_ARG]);
                    $group->setVisual($groupVisual);
                } 
                $this->changeGroupName($group, $answer, $driver, $isAdminOwner, $isAdmin);
            }

//            if (isset($parameters[self::GROUPIMG_ARG])) {
//                $this->updateGroupImage();
//            }
            // Caso desejemos modificar os objetos que pertencem a esse grupo
            if (isset($parameters[self::OBJECTS_ARG])) {
                $this->updateGroupObjects($parameters,$group,$answer,$driver);
            }
        }
    }

    //FIXME: please fix this thing!
    private function changeGroupName(HomeGroup $group, $answer, $driver, $isAdminOwner, $isAdmin) {
        $groupId = $group->getStructure()->getId();
        $groupName = $group->getVisual()->getGroupName();
        $query = null;
        if (($isAdminOwner && $isAdmin) || (!$isAdminOwner && !$isAdmin)) {
            // Se o grupo pertence ao administrador e o usuário é administrador
            // OU Se o grupo é local
            // RESULTADO: Mudamos diretamente
            if ($this->checkPermission($groupId, $groupId, $answer, $driver)) {
                $query = $this->changeDefaultName($groupId, $groupName);
            }
        } else if ($isAdminOwner && !$isAdmin) {
            // Se o grupo é um cômodo e o usuário é comum
            // RESULTADO: Mudamos apenas para o perfil do user
            $query = $this->changeName($groupId, $groupName);
        }

        if (is_null($query)) {
            $answer->setStatus(0);
            $answer->setMessage('@update_group_error');
        } else {
            $driver->exec($query->getInstruction());
        }
    }

    private function checkPermission($groupId, $userId, $answer, $driver) {
        $permission = new UserGroups($userId, $driver);

        if (!$permission->verifyRights($groupId) || ($groupId == 0 && $userId != 0)) {
            // Se o usuário não tem permissão de edição ou o grupo é um cômodo
            $answer->setStatus(0);
            $answer->setMessage('@forbidden');
            return false;
        }
        return true;
    }

//    private function updateGroupImage() {
//        $imageId = $parameters[self::GROUPIMG_ARG];
//        $imagePermissions = new UserImages($userId, $driver);
//        if (!is_numeric($imageId)) {
//            $answer->setStatus(0);
//            $answer->setMessage('@not_image');
//        } else if (!$imagePermissions->verifyRights($imageId)) {
//            $answer->setStatus(0);
//            $answer->setMessage('@forbidden');
//        } else {
//            //TODO
//        }
//    }

    private function updateGroupObjects($parameters, HomeGroup $group, $answer, $driver) {
        $groupId = $parameters[self::GROUP_ARG];
        if ($this->checkObjectParameter($parameters, $answer) 
                && $this->checkPermission($groupId, $group->getUserId(), $answer, $driver)) {
            $this->removeGroupElements($groupId, $driver);
            $this->insertGroupElements($parameters[self::OBJECTS_ARG], $groupId, $group->getUserId(), $driver);
        }
    }

    private function checkObjectParameter($parameters, $answer) {
        if (!is_array($parameters[self::OBJECTS_ARG])) {
            // Se 'objects' não é um array
            $answer->setStatus(0);
            $answer->setMessage('@group_not_collection');
            return false;
        }
        return true;
    }

    private function removeGroupElements($groupId, $driver) {
        // Se está tudo bem e podemos alterar os objetos
        // Removendo os itens anteriores
        $delete = new DeleteQuery();
        $delete->setEntity(GroupElementsTable::TABLE_NAME);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter('group_id', '=', $groupId));
        $delete->setCriteria($criteria);

        $driver->exec($delete->getInstruction());
    }

    private function insertGroupElements($objectsId, $groupId, $userId, $driver) {
        // Adicionando os novos itens
        $permissions = new UserViews($userId, $driver);
        $elementDAO = new GroupElementDAO($driver);
        foreach ($objectsId as $objId) {
            if ($permissions->verifyRights($objId)) {
                $element = new GroupElement();
                $element->setGroupId($groupId);
                $element->setObjectId($objId);

                $elementDAO->insert($element);
            }
        }
    }

    // Change the group default name
    private function changeDefaultName($groupId, $newName) {
        $update = new UpdateQuery();
        $update->setEntity(GroupVisualTable::TABLE_NAME);
        $update->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $newName);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(GroupVisualTable::COLUMN_GROUP_ID, '=', $groupId));
        $update->setCriteria($criteria);

        return $update;
    }

    // Change the group label
    private function changeName($groupId, $newName, $userId, PDO $driver) {
        if ($this->existVisual()) {
            return $this->updateVisual();
        } else {
            return $this->insertVisual();
        }
    }

    private function existVisual() {
        $select = new SelectQuery();
        $select->addColumn(GroupVisualTable::COLUMN_ID);
        $select->setEntity(GroupVisualTable::TABLE_NAME);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter(GroupVisualTable::COLUMN_USER_ID, '=', $userId), SqlExpression::AND_OPERATOR);
        $criteria->add(new SqlFilter(GroupVisualTable::COLUMN_GROUP_ID, '=', $groupId), SqlExpression::AND_OPERATOR);
        $select->setCriteria($criteria);

        $statement = $driver->query($select->getInstruction());

        return ($statement->rowCount() > 0);
    }

    private function updateVisual() {
        while ($rs = $statement->fetch(PDO::FETCH_ASSOC)) {
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
    }

    private function insertVisual() {
        // Insert
        $insert = new InsertQuery();
        $insert->setEntity(GroupVisualTable::TABLE_NAME);

        $insert->setRowData(GroupVisualTable::COLUMN_USER_ID, $userId);
        $insert->setRowData(GroupVisualTable::COLUMN_GROUP_ID, $groupId);
        $insert->setRowData(GroupVisualTable::COLUMN_GROUP_NAME, $newName);

        return $insert;
    }

}

?>