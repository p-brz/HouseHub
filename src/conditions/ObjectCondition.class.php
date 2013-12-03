<?php
namespace househub\conditions;

class ObjectCondition{
    private $name;
    private $restrictions;

    public function __construct($name){
        $this->name = $name;
        $this->restrictions = array();
    }

    public function getName(){return $this->name;}
    public function setName($name){$this->name = $name;}

    public function addRestriction($statusName, $expectedValue){
        $this->restrictions[$statusName] = $expectedValue;
    }
    public function getRestriction($statusName){
        return $this->restrictions[$statusName];
    }
    public function removeRestriction($statusName){
        unset($this->restrictions[$statusName]);
    }

    public function isValid($status){
        $isValid = true;
        $index = 0;

        foreach($this->restrictions as $statusName => $expectedValue){
            if(isset($status[$index])){
                $isValid = $isValid && ($status[$index]->getValue() == $expectedValue);
            }
            else{
                $isValid = false;
            }
            $index++;
        }
        return $isValid;
    }
}
