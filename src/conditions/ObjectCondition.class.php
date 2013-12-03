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
        if(isset($this->restrictions[$statusName])){
            return array($statusName,$this->restrictions[$statusName]);
        }
        
        return null;
    }
    public function removeRestriction($statusName){
        unset($this->restrictions[$statusName]);
    }

    /* Por hora as restrições envolvem apenas a igualdade.
     * Ou seja, ela será valida quando todos os valores em $status forem iguais aos valores
     * esperados nas restrictions.
     * Futuramente, implementar operações mais complexas (operadores) 
     */
    public function isValid($status){

        foreach($this->restrictions as $statusName => $expectedValue){
            $state = $this->findStatus($statusName, $status);
            if(is_null($state) || $state->getValue() != $expectedValue){
                return false;
            }
        }
        return true;
    }
    
    private function findStatus($name,$status){
        foreach($status as $state){
            if($state->getName() == $name){
                return $state;
            }
        }
        return null;
    }
}
