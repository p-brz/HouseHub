<?php
namespace househub\objects\home;

use househub\objects\tables\ObjectStructureTable;

use househub\status\dao\StatusStructureDAO;

use househub\services\dao\ServiceStructureDAO;

use househub\objects\tables\ObjectVisualIconpackTable;

use househub\objects\dao\ObjectVisualIconpackDAO;

use househub\objects\tables\ObjectVisualNameTable;

use househub\objects\dao\ObjectVisualNameDAO;

use househub\scheme\SchemeLoader;

use househub\scheme\SchemeParser;

use househub\scheme\SchemeJsonFileReader;

use lightninghowl\utils\StrOpers;

use househub\readers\SystemReader;

use househub\objects\ObjectStructure;

use lightninghowl\utils\sql\SelectQuery;

use househub\objects\tables\ObjectSubObjectsTable;

use househub\status\builders\StatusStructureBuilder;

use househub\status\tables\StatusStructureTable;

use househub\services\builders\ServiceStructureBuilder;

use househub\services\tables\ServiceStructureTable;

use lightninghowl\utils\sql\SqlExpression;

use househub\objects\tables\ObjectVisualTable;

use lightninghowl\utils\sql\SqlFilter;

use lightninghowl\utils\sql\SqlCriteria;

use househub\objects\dao\ObjectVisualDAO;

use househub\objects\dao\ObjectStructureDAO;

use househub\objects\home\HomeObject;

use PDO;

class HomeObjectManager{
    public function loadObject($identifier, $userId, PDO $driver){
            $object = new HomeObject();

            $object->setStructure($this->loadStructure($identifier, $driver));
            $object->setVisualName($this->loadVisualName($identifier, $userId, $driver));
            $object->setVisualIconpack($this->loadVisualIconpack($identifier, $userId, $driver));
            $object->setServices($this->loadServices($identifier, $driver));
            $object->setStatus($this->loadStatus($identifier, $driver));
            $object->setSubObjects($this->loadSubObjects($identifier, $userId, $driver));
            $object->setScheme($this->loadScheme($object->getStructure()));

            return $object;
    }

    /**
     * 
     * @param integer $identifier
     * @param PDO $driver
     * @return ObjectStructure 
     */
    public function loadStructure($identifier, PDO $driver){
        if(!is_numeric($identifier)){
                return null;
        }

        $dao = new ObjectStructureDAO($driver);
        $structure = $dao->load($identifier);

        return $structure;
    }

    public function loadVisualName($identifier, $userId, $driver){
            if(!is_numeric($identifier) || !is_numeric($userId)){
                return null;
            }

            $visualName = null;
            $visualDAO = new ObjectVisualNameDAO($driver);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(ObjectVisualNameTable::COLUMN_OBJECT_ID, '=', $identifier), SqlExpression::AND_OPERATOR);

            $subCriteria = new SqlCriteria();
            $subCriteria->add(new SqlFilter(ObjectVisualNameTable::COLUMN_USER_ID, '=', 0), SqlExpression::OR_OPERATOR);
            $subCriteria->add(new SqlFilter(ObjectVisualNameTable::COLUMN_USER_ID, '=', $userId), SqlExpression::OR_OPERATOR);

            $criteria->add($subCriteria);
            $visuals = $visualDAO->listAll($criteria);

            foreach($visuals as $singleVisual){
                    if(is_null($visualName)){
                            $visualName = $singleVisual;
                    }else if($visualName->getUserId() == 0 && $singleVisual->getUserId() > 0){
                            $visualName = $singleVisual;
                    }
            }

            return $visualName;
    }

    public function loadVisualIconpack($identifier, $userId, $driver){
            if(!is_numeric($identifier) || !is_numeric($userId)){
                    return null;
            }

            $visualIconpack = null;
            $visualDAO = new ObjectVisualIconpackDAO($driver);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(ObjectVisualIconpackTable::COLUMN_OBJECT_ID, '=', $identifier), SqlExpression::AND_OPERATOR);

            $subCriteria = new SqlCriteria();
            $subCriteria->add(new SqlFilter(ObjectVisualIconpackTable::COLUMN_USER_ID, '=', 0), SqlExpression::OR_OPERATOR);
            $subCriteria->add(new SqlFilter(ObjectVisualIconpackTable::COLUMN_USER_ID, '=', $userId), SqlExpression::OR_OPERATOR);

            $criteria->add($subCriteria);
            $visuals = $visualDAO->listAll($criteria);

            foreach($visuals as $singleVisual){
                    if($visualIconpack == null){
                            $visualIconpack = $singleVisual;
                    }else if($visualIconpack->getUserId() == 0 && $singleVisual->getUserId() > 0){
                            $visualIconpack = $singleVisual;
                    }
            }

            return $visualIconpack;
    }

    public function loadServices($identifier, PDO $driver){
            $services = array();

            $select = new SelectQuery();
            $select->addColumn('*');
            $select->setEntity(ServiceStructureTable::TABLE_NAME);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(ServiceStructureTable::COLUMN_OBJECT_ID, '=', $identifier));
            $select->setCriteria($criteria);

            $statement = $driver->query($select->getInstruction());
            $builder = new ServiceStructureBuilder();
            while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
                    $services[] = $builder->build($rs);
            }

            return $services;
    }

    public function loadStatus($identifier, PDO $driver){
            $statusArray = array();

            $select = new SelectQuery();
            $select->addColumn('*');
            $select->setEntity(StatusStructureTable::TABLE_NAME);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(StatusStructureTable::COLUMN_OBJECT_ID, '=', $identifier));
            $select->setCriteria($criteria);

            $statement = $driver->query($select->getInstruction());
            $builder = new StatusStructureBuilder();
            while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
                    $statusArray[] = $builder->build($rs);
            }

            return $statusArray;
    }

    public function loadSubObjects($identifier, $userId, PDO $driver){
            $objects = array();

            $select = new SelectQuery();
            $select->addColumn(ObjectStructureTable::COLUMN_ID);
            $select->setEntity(ObjectStructureTable::TABLE_NAME);

            $criteria = new SqlCriteria();
            $criteria->add(new SqlFilter(ObjectStructureTable::COLUMN_PARENT_ID, '=', $identifier));
            $select->setCriteria($criteria);

            $statement = $driver->query($select->getInstruction());
            while($rs = $statement->fetch(PDO::FETCH_ASSOC)){
                    $objects[] = $this->loadObject(intval($rs[ObjectStructureTable::COLUMN_ID]), $userId, $driver);
            }

            return $objects;
    }

    public function loadScheme($structure){
            if(is_null($structure)){
                return null;
            }

            //TODO: refatorar isto! Remover referencia a _SERVER
            $sysRes = SystemReader::getInstance();
            $path  = $_SERVER['DOCUMENT_ROOT']; 
            $path .= DIRECTORY_SEPARATOR.$sysRes->translate(SystemReader::INDEX_ROOTPATH);
            $path .= DIRECTORY_SEPARATOR.$sysRes->translate(SystemReader::INDEX_SCHEMES);

            //TODO: refatorar isto! criar ponto de acesso a um SchemeLoader estatico
            $jsonReader = new SchemeJsonFileReader(StrOpers::strFixPath($path));
            $scheme = $jsonReader->getScheme($structure->getSchemeName());

            return $scheme;
    }

    public function saveObject(HomeObject $homeObject, PDO $driver, $saveThisOnly = false){
        $savedStructure = $this->saveObjectStructure($homeObject->getStructure(), $driver);
        
        $savedServices = 
                $this->saveServices($savedStructure->getId(), $homeObject->getServices(), $driver);
        $savedStatus   = 
                $this->saveStatus  ($savedStructure->getId(), $homeObject->getStatus()  , $driver);

        if(!$saveThisOnly){
            $savedSubobjects =
                $this->saveSubobjects($savedStructure->getId(), $homeObject->getSubObjects(), $driver);
        
            $homeObject->setSubObjects($savedSubobjects);
        }
        
        $homeObject->setStructure($savedStructure);
        $homeObject->setServices($savedServices);
        $homeObject->setStatus($savedStatus);

        return $homeObject;
    }
    
    public function saveObjectStructure(ObjectStructure $structure, $driver){
//        $structure = $homeObject->getStructure();

        $objectDAO = new ObjectStructureDAO($driver);
        $objectId = $objectDAO->insert($structure);
//        $structure->setId($objectId);
        
        $savedStructure = $objectDAO->load($objectId);
        
        
//        $homeObject->setStructure($structure);
        return $savedStructure;
    }    
    public function saveServices($objectId, $services, $driver){
//        $objectId = $homeObject->getStructure()->getId();
        
//        $services = $homeObject->getServices();
        if(!empty($services) && !is_null($services)){
                $serviceDAO = new ServiceStructureDAO($driver);
                foreach($services as $key=>$service){
                        $service->setObjectId($objectId);
                        $serviceId = $serviceDAO->insert($service);
                        $service->setId($serviceId);
                        $services[$key] = $service;
                }
        }
//        $homeObject->setServices($services);
        return $services;
    }
    public function saveStatus($objectId, $status, $driver){
//        $objectId = $homeObject->getStructure()->getId();
        
//        $status = $homeObject->getStatus();
        if(!empty($status) && !is_null($status)){
                $statusDAO = new StatusStructureDAO($driver);
                foreach($status as $key=>$singleStatus){
                        $singleStatus->setObjectId($objectId);
                        $statusId = $statusDAO->insert($singleStatus);
                        $singleStatus->setId($statusId);
                        $status[$key] = $singleStatus;
                }	
        }
//        $homeObject->setStatus($status);
        return $status;
    }
    
    public function saveSubobjects($objectId, $subObjects, $driver){
//        $objectId = $homeObject->getStructure()->getId();
        
//        $subObjects = $homeObject->getSubObjects();
        foreach($subObjects as $key=>$subObject){
                $subObject->getStructure()->setParentId($objectId);
                $subObject->getStructure()->setParentIndex($key);

                $obj = $this->saveObject($subObject, $driver, true);
                $subObjects[$key] = $obj;	
        }
//        $homeObject->setSubObjects($subObjects);
        return $subObjects;
    }
}
?>