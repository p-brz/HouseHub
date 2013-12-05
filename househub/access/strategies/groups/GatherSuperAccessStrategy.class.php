<?php

namespace househub\access\strategies\groups;
/**
 * Gather the super categories
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 *
 */

use PDO;

/**
 * @todo Classe ainda não implementada completamente
 * @codeCoverageIgnore
 */
class GatherSuperAccessStrategy extends \househub\access\strategies\AbstractAccessStrategy{
    public function requestAccess($parameters){
        $answer = $this->initializeAnswer();
        $driver = DatabaseConnector::getDriver();

        $sessManager = SessionManager::getInstance();
        $userId = $sessManager->getSessionVariable('user_id');
        if(is_null($userId)){
            $answer->setMessage('@user_needs_login');
        }else{
            $answer->setStatus(1);
            $answer->setMessage('@success');

            // Para o grupo home
            if(isset($parameters['home'])){
                $this->gatherHomeCategory($userId,$answer, $driver);
            }

            // Para o grupo de appliances
            if(isset($parameters['appliances'])){
                $this->gatherAppliancesCategory($userId, $answer, $driver);
            }

            // Para o grupo others
            if(isset($parameters['others'])){
                $this->gatherOtherCategory($userId,$answer, $driver);
            }

            // Para os objetos pendentes de validação
            if(isset($parameters['blocked'])){
                $this->gatherBlockedObjects($userId,$answer, $driver);
            }

            if(isset($parameters['validated'])){
               $this->gatherValidatedObjects($userId,$answer, $driver);
            }

        }

        return $answer;

    }

    protected function gatherHomeCategory($userId,&$answer, $driver){
        $home = new JsonArray('home');
        $homeQuery = new SelectQuery();
        $homeQuery->addColumn('id');
        $homeQuery->setEntity(BlockCategoryDAO::CATEGORY_TABLE);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter('user_id', '=', 0));
        $homeQuery->setCriteria($criteria);

        $statement = $driver->query($homeQuery->getInstruction());
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $catArray = array();
        while($rs = $statement->fetch()){
                $catArray[] = $rs['id'];
        }

        $loader = new CategoryLoader();
        $catParser = new CategoryJsonParser();
        foreach($catArray as $cat){
                $myCategory = $loader->load($cat, $driver);
                $jsonCategory = $catParser->categoryToJson($myCategory, $userId);
                $home->addElement($jsonCategory);
        }

        if($answer->getStatus() == 1){
                $answer->addContent($home);
        }
    }

    protected function gatherAppliancesCategory($userId,&$answer, $driver){
        $appliances = new JsonArray('appliances');
        //TODO: 
    }

    protected function gatherOtherCategory($userId,&$answer, $driver){
        $others = new JsonArray('others');

        $othersQuery = new SelectQuery();
        $othersQuery->addColumn('id');
        $othersQuery->setEntity(BlockCategoryDAO::CATEGORY_TABLE);

        $criteria = new SqlCriteria();
        $criteria->add(new SqlFilter('user_id', '=', $userId));

        $othersQuery->setCriteria($criteria);

        $statement = $driver->query($othersQuery->getInstruction());
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $catArray = array();
        while($rs = $statement->fetch()){
                $catArray[] = $rs['id'];
        }

        $loader = new CategoryLoader();
        $catParser = new CategoryJsonParser();
        foreach($catArray as $cat){
                $myCategory = $loader->load($cat, $driver);
                $jsonCategory = $catParser->categoryToJson($myCategory, $userId);
                $others->addElement($jsonCategory);
        }

        if($answer->getStatus() == 1){
                $answer->addContent($others);
        }
    }

    protected function gatherBlockedObjects($userId,&$answer, $driver){
        if($userId != 0){
            $answer->setMessage('@forbidden');

        }else{
            $blocked = new JsonArray('blocked');

            $blockedQuery = new SelectQuery();
            $blockedQuery->addColumn('id');
            $blockedQuery->setEntity(BlockObjectDAO::OBJECT_TABLE);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter('validated', '=', 0));
            $blockedQuery->setCriteria($criteria);

            $statement = $driver->query($blockedQuery->getInstruction());
            $statement->setFetchMode(PDO::FETCH_ASSOC);

            $loader = new ObjectLoader();
            $objParser = new ObjectJsonParser();

            while($rs = $statement->fetch()){
                    $object = $loader->load($rs['id'], $driver);
                    $objJson = $objParser->objectToJson($object, $userId);
                    $blocked->addElement($objJson);
            }

            if($answer->getStatus() == 1){
                    $answer->addContent($blocked);
            }
        }
    }

    protected function gatherValidatedObjects($userId,&$answer, $driver){
        $jsonValidated = new JsonArray('validated');
        $objectArray = array();
        $loader = new ObjectLoader();
        if($userId != 0){
                $permissions = new UserViews($userId, $driver);
                foreach($permissions->getRights() as $objectId){
                        $objectArray[] = $loader->load($objectId, $driver);
                }
        }else{
                $validated = new SelectQuery();

                $validated->addColumn('id');
                $validated->setEntity(BlockObjectDAO::OBJECT_TABLE);

                $criteria = new SqlCriteria();
                $criteria->add(new SqlFilter(BlockObjectDAO::COLUMN_VALIDATED, '=', 1));
                $validated->setCriteria($criteria);

                $statement = $driver->query($validated->getInstruction());
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                while($rs = $statement->fetch()){
                        $objectArray[] = $loader->load($rs['id'], $driver);
                }
        }

        $parser = new ObjectJsonParser();
        foreach($objectArray as $object){
                $jsonValidated->addElement($parser->objectToJson($object, $userId));
        }

        $answer->addContent($jsonValidated);
    }
}

?>