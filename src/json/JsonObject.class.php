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
        
        private function filterIndex($index){
            if(is_numeric($index) && isset($this->elements[$index])){
                return $index;
            }
            else if(is_string($index)){
                $i = 0;
                foreach($this->elements as $elem){
                    if($elem->getName() == $index){
                        return $i;
                    }
                    $i = $i +1;
                }
            }
            return null;
        }
	public function getElement($index){
            $newIndex = $this->filterIndex($index);
            if(!is_null($newIndex)){
                return $this->elements[$newIndex];
            }
            
            return null;//não enontrou
	}
	public function setElement($index, JsonElement $element){
		$this->elements[$index] = $element;
	}
	
	public function addElement(JsonElement $element){ //TODO: testar tipo de $element?
		$this->elements[] = $element;
	}
	public function removeElement(JsonElement $element){ //TODO: testar tipo de $element?
//            $elementIndex = -1;
//            for($i =1; $i < count($this->elements) && $elementIndex < 0; $i++){
//                if($this->elements[$i] == $element){
//                    $elementIndex = $i;
//                }
//            }

//            if($elementIndex > 0){
//                $this->elements = array_splice($this->elements, $elementIndex, 1);//Remove elemento
//            }
            $index = array_search($element, $this->elements);
            if($index != false || $index == 0){
                unset($this->elements[$index]);
                $this->elements = array_values($this->elements);
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
}

?>
