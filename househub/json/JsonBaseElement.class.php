<?php

namespace househub\json;

/** implementa alguns comportamentos básicos para reutilizar código*/
abstract class JsonBaseElement extends JsonElement{
    protected $name;
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function toString(){
		$stringJson = '';
		
		if(!empty($this->name) && $this->name != ''){
			$stringJson = "\"".$this->name."\"";
                        $stringJson .= " : ";
                        $stringJson .= $this->valueToString();
		}
		
		return $stringJson;
	}
}

?>
