<?php

namespace househub\json;

abstract class JsonElement{
	abstract public function getName();
	abstract public function setName($name);
	
	abstract public function toString();
    abstract public function valueToString();

    /** Converte este objeto para string ao utilizar o cast (string)*/
    public function __toString(){
        //delega para o metodo toString
        return $this->toString();
    }
}

?>
