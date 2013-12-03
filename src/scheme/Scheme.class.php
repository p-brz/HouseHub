<?php

namespace househub\scheme;

class Scheme{
    private $schemeName;
    private $schemeVersion;
    private $objType;
    private $configs;
    private $status;
    private $services;
    private $conditions;

    public function getSchemeName(){  return $this->schemeName ;}
    public function getSchemeVersion(){  return $this->schemeVersion ;}
    public function getObjectType(){  return $this->objType ;}
    public function getConfigs(){  return $this->configs ;}
    public function getStatus(){  return $this->status ;}
    public function getServices(){  return $this->services ;}
    public function getConditions(){  return $this->conditions ;}

    public function setSchemeName($schemeName) {$this->schemeName = $schemeName;}
    public function setSchemeVersion($schemeVersion) {$this->schemeVersion = $schemeVersion;}
    public function setObjectType($objType){  $this->objType = $objType;}
    public function setConfigs($configs) {$this->configs = $configs;}
    public function setStatus($status) {$this->status = $status;}
    public function setServices($services) {$this->services = $services;}
    public function setConditions($conditions) {$this->conditions = $conditions;}
}

?>
