<?php

namespace househub\scheme;

class SchemeProperty{
	private $name;
	private $path;
	private $type;
	private $allowedValues;
    private $isReadOnly;
	
	public function __construct($name, $path, $type = SchemeType::unknownT, $isReadOnly = false, $allowedValues = array()){
		$this->name = $name;
		$this->path = $path;
		$this->type = $type;
        $this->isReadOnly = $isReadOnly;
		$this->allowedValues = $allowedValues;
	}

    public function getName(){  return $this->name;}
    public function getPath(){  return $this->path;}
    public function getType(){  return $this->type;}
    public function getAllowedValues(){  return $this->allowedValues;}
    public function isReadOnly(){ return $this->isReadOnly;}

    public function setName($name){  $this->name = $name;}
    public function setPath($path){  $this->path = $path;}
    public function setType($type){  $this->type = $type;}
    public function setAllowedValues($allowedValues){  $this->allowedValues = $allowedValues;}
    public function setReadOnly($readOnly){$this->isReadOnly = $readOnly;}
	
}

?>
