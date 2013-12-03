<?php

namespace househub\scheme;

class SchemeJsonFileReader implements SchemeReader{
    private $filepath;
	
    public function __construct($directory){
        $this->filepath = $directory;
    }

    public function getFilepath(){
        return $this->filepath;
    }
    public function setFilepath($filepath){
        $this->filepath = $filepath;
    }

    private function loadJson($file){
        $jsonArray = array();

        if(!empty($file) && file_exists($file)){
	        $json_configs = file_get_contents($file);
	        $jsonArray = json_decode($json_configs, true);
        }
        return $jsonArray;
    }

//TODO: getters and setter (configReader)
    public function getScheme($name){
        if(!empty($name) && !empty($this->filepath)){
            $configs = $this->loadJson($this->filepath . DIRECTORY_SEPARATOR. $name . ".json");
			
            return SchemeParser::getInstance()->convertToScheme($configs);
        }
        return null;
    }

}

?>
