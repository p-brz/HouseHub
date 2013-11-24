<?php

namespace lightninghowl\reader;

class JsonReader{
	
	private $informations;
	
	public function __construct($jsonFile){
		$this->informations = array();
		if(is_file($jsonFile)){
			$content = file_get_contents($jsonFile);
			$this->informations = json_decode($content, true);
		}
	}
	
	
	public function get($property = ''){
		
		if(empty($property)){
			return $this->informations;
		}else if(isset($this->informations[$property])){
			return $this->informations[$property];
		}else{
			return null;
		}
	}
	
}

?>