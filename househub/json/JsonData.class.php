<?php

namespace househub\json;

class JsonData extends JsonBaseElement{
    protected $value;

	public function __construct($name = "", $value = ""){
		$this->name  = $name;
		$this->value = $value;
	}

	public function getValue(){
		return $this->value;
	}
	public function setValue($value){
		$this->value = $value;
	}

    public function valueToString(){
        return JsonUtil::parseValueToString($this->value);
    }
}

?>
