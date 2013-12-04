<?php
namespace househub\objects\home;

class HomeObject{
	
    /**
     * @var \househub\object\ObjectStructure
     */
    private $structure;
    /**
     * @var \househub\object\ObjectVisualName
     */
    private $visualName;
    private $visualIconpack;
    private $services;
    private $status;
    private $subObjects;
    private $scheme;

    public function getId(){
        return (!is_null($this->structure) ? $this->structure->getId() : null);
    }
    public function getStructure() { return $this->structure; } 
    public function getVisualName() { return $this->visualName; } 
    public function getVisualIconpack() { return $this->visualIconpack; } 
    public function getServices() { return $this->services; } 
    public function getStatus() { return $this->status; } 
    public function getSubObjects() { return $this->subObjects; }
    public function getScheme() { return $this->scheme; } 

    
    public function setId($id){
        if(!is_null($this->structure)){
            $this->structure->setId($id);
        }
    }
    public function setStructure($x) { $this->structure = $x; } 
    public function setVisualName($x) { $this->visualName = $x; } 
    public function setVisualIconpack($x) { $this->visualIconpack = $x; } 
    public function setServices($x) { $this->services = $x; } 
    public function setStatus($x) { $this->status = $x; } 
    public function setSubObjects($x) { $this->subObjects = $x; }
    public function setScheme($x) { $this->scheme = $x; } 

    public function getValidConditions(){
        $validConditions = array();
        
        if(!is_null($this->scheme)){
            foreach($this->scheme->getConditions() as $condition){
                if($condition->isValid($this->status)){
                    $validConditions[] = $condition;
                }
            }
        }
        
        return $validConditions;
    }
}
?>