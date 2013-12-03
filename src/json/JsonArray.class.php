<?php

namespace househub\json;

class JsonArray extends JsonBaseElement{
	protected $elements;

	public function __construct($name = "", $elements = array()){
		$this->name  = $name;
		$this->elements = $elements;
	}

	public function getElements(){
		return $this->elements;
	}

	public function getElement($index){
		return $this->elements[$index];
	}
	public function setElement($index, $value){
		$this->elements[$index] = $value;
	}
	
	public function addElement($value){ //TODO: testar tipo de $element?
		$this->elements[] = $value;
	}
//	public function removeElement($value){ //TODO: testar tipo de $element?
//            $elementIndex = -1;
//            for($i =0; $i < count($this->elements) && $elementIndex < 0; $i++){
//                if($this->elements[$i] == $value){
//                    $elementIndex = $i;
//                }
//            }
//            
//            if($elementIndex >= 0){
//                $this->elements = array_splice($this->elements, $elementIndex, 1);//Remove elemento
//            }
//	}
	public function removeElement($value){ //TODO: testar tipo de $element?
            $elementIndex = array_search($value, $this->elements);
            
            if(is_numeric($elementIndex)){
                array_splice($this->elements, $elementIndex, 1);//Remove elemento
                
            }
	}

    /**
        @Override
    */
    public function valueToString(){
        $stringJson = "[ ";
        if(!empty($this->elements)){
            for($i =0; $i < count($this->elements); $i++){
                $element = $this->elements[$i];
                if($i > 0){
                    $stringJson .= ", ";
                }
                if(is_object($element) && $element instanceof JsonElement){
                    $stringJson .= $element->valueToString();
                }
                else{
                    $stringJson .= JsonUtil::parseValueToString($this->elements[$i]);
                }
            }
        }
        $stringJson .= " ]";
        return $stringJson;
    }
}

?>
