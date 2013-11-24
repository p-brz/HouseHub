<?php

namespace househub\json;

class JsonObject extends JsonBaseElement{
	protected $elements;

	public function __construct($name = ""){
		$this->name  = $name;
		$this->elements = array();
	}

	public function getElements(){
		return $this->elements;
	}

	public function getElement(int $index){
		return $this->elements[$index];
	}
	public function setElement(int $index, JsonElement $element){
		$this->elements[$index] = $element;
	}
	
	public function addElement(JsonElement $element){ //TODO: testar tipo de $element?
		$this->elements[] = $element;
	}
	public function removeElement(JsonElement $element){ //TODO: testar tipo de $element?
        $elementIndex = -1;
        for($i =1; $i < count($this->elements) && $elementIndex < 0; $i++){
            if($this->elements[$i] == $element){
                $elementIndex = $i;
            }
        }

        if($elementIndex > 0){
            $this->elements = array_splice($this->elements, $elementIndex, 1);//Remove elemento
        }
	}

    /**
        @Override
    */
    public function valueToString(){
        $stringJson = "{ ";
        if(!empty($this->elements)){
            $stringJson .= $this->elements[0]->toString();
	        for($i =1; $i < count($this->elements); $i++){
                $stringJson .= ", ";
                $stringJson .= $this->elements[$i]->toString();
            }
        }
        $stringJson .= "}";
        return $stringJson;
    }
    
    public function toString(){
    	$stringJson = '"'.$this->name.'"';
    	$stringJson .= ' : ';
    	$stringJson .= $this->valueToString();
    	
    	return $stringJson;
    }
}$content = new JsonObject("content");

?>
