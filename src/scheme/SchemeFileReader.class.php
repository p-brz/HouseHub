<?php

namespace househub\scheme;

class SchemeFileReader implements SchemeReader{
    private $filepath;
    private $configReader;

    public function __construct(ConfigFileReader $configFileReader, $directory = ""){
        $this->filepath = $directory;
        $this->configReader = $configFileReader;
    }

    public function getFilepath(){
        return $this->filepath;
    }
    public function setFilepath($filepath){
        $this->filepath = $filepath;
    }

//TODO: getters and setter (configReader)

    public function getScheme($name){
        if(!empty($name) && !empty($this->configReader) && !empty($this->filepath)){
            //FIXME: desacoplar json
            $this->configReader->setFilepath($this->filepath . DIRECTORY_SEPARATOR. $name . ".json"); 
            $configs = $this->configReader->getConfigs();

            return $this->makeScheme($configs);
        }
    }

    protected function makeScheme($configs){
        echo "<pre>"; var_dump($configs); echo "</pre>";
        $name         = $configs["scheme"];
        $version      = $configs["version"];
        $languagePack = $this->makeLanguagePack($configs["language_pack"]);
        $objConfigs   = $this->makeProperties($configs["configs"]);
        $objStatus    = $this->makeProperties($configs["status"]);
        $objServices  = $this->makeServices($configs["services"]);
        $objStates    = $this->makeStates($configs["states"]);

        $scheme = new Scheme();
        $scheme->setName($name);
        $scheme->setVersion($version);
        $scheme->setLanguagePack($languagePack);
        $scheme->setConfigs($objConfigs);
        $scheme->setStatus($objStatus);
        $scheme->setServices($objServices);      
        $scheme->setStates($objStates);

        return $scheme;
    } 
    protected function makeLanguagePack($languagePackConfigs){
        return null;
    }

    protected function makeProperties($confs){
        if(!empty($confs)){
            foreach(array_keys($confs) as $key){
                $schemeConfs[] = $this->makeProperty($confs[$key]);
            }
        }

        return $schemeConfs;
    }

    protected function makeProperty($confProperty){
        $propName = $confProperty["name"];
        $propPath = $confProperty["path"];
        $propType = $confProperty["type"];
        $propReadOnly = (isset($confProperty["readOnly"]) ? $confProperty["readOnly"] : false);
        $propIsReadOnly = (!is_null($propReadOnly) ? $propReadOnly : false);

        $propAllowedValue = array();

        if(isset($confProperty["allowedValues"])){
            $propAllowedValue = $this->makeAllowedValues($confProperty["allowedValues"]);
        }

        $property = new SchemeProperty($propName, $propPath, $propType, $propReadOnly, $propAllowedValue);
        
        return $property;
    }

    protected function makeAllowedValues($allowedValues){
        $propAllowedValue = array();
        foreach($allowedValues as $allowed){
            $type = $allowed["type"];

            $values = array();
            foreach($allowed["values"] as $value){
                $values[] = $value;
            }

            if($type == "range"){//FIXME: not open-closed :(
                //echo "<pre>"; var_dump($values); echo "</pre>";
                $propAllowedValue[] = new RangeValue($values[0], $values[1]);
            }
            else if($type == "enum"){
                $propAllowedValue[] = new EnumValue($values);
            }
        }
        return $propAllowedValue;
    }

    protected function makeServices($confServices){
        return null;
    }

    protected function makeStates($confStates){
        return null;
    }

}

?>
