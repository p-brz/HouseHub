<?php
namespace househub\scheme;

use lightninghowl\utils\StrOpers;

use househub\scheme\Scheme;

use househub\conditions\ObjectCondition;

class SchemeParser{
    private static $instance = null;

    public static function getInstance(){
        if(SchemeParser::$instance == null){
            SchemeParser::$instance = new SchemeParser();
        }
        return SchemeParser::$instance;
    }

	public function convertToScheme($schemeArray){
        return $this->makeScheme($schemeArray);
    }

    /**
     * Recebe o array associativo json e retorna o objeto scheme preenchido
     * @param array $configs
     * @return Scheme
     */
    protected function makeScheme($configs){
    	if(is_null($configs) || empty($configs)){
    		return null;
    	}
    	
        //echo "<pre>"; var_dump($configs); echo "</pre>";
        $schemeName    = $configs["scheme"];
        $schemeVersion = $configs["version"];
        $objType       = $configs["type"];
        $objConfigs    = $this->makeProperties($configs["configs"]);
        $objStatus     = $this->makeProperties($configs["status"]);
        $objServices   = $this->makeServices($configs["services"]);
        $objConditions = $this->makeConditions($configs["conditions"]);

        $scheme = new Scheme();
        $scheme->setSchemeName($schemeName);
        $scheme->setSchemeVersion($schemeVersion);        
        $scheme->setObjectType($objType);
        $scheme->setConfigs($objConfigs);
        $scheme->setStatus($objStatus);
        $scheme->setServices($objServices);      
        $scheme->setConditions($objConditions);
        
        return $scheme;
    } 


    protected function makeProperties($confs){
    	$schemeConfs = null;
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

/**

    "services" : [
        {
            "name"  : "travar",
            "path"  : "/services/travar",
            "returnType" : "boolean",
            "text"  : "@travar",
            "undoService" : "destravar",
            "parameters" : []
        },
        {
            "name"  : "destravar",
            "path"  : "/services/destravar",
            "returnType" : "boolean",
            "text"  : "@destravar",
            "undoService" : "travar",
            "parameters" : []
        }
    ],
*/

    protected function makeServices($confServices){
        $services = array();
        foreach($confServices as $confServ){
            $schemeService = new SchemeService();

            $schemeService->setName($confServ["name"]);
            $schemeService->setPath($confServ["path"]);
            $schemeService->setReturnType($confServ["returnType"]);
            $schemeService->setText($confServ["text"]);
            $schemeService->setUndoService($confServ["undoService"]);

            foreach($confServ["parameters"] as $parameter){
                $schemeService->addParameter($this->makeProperty($parameter));
            }

            $services[] = $schemeService;
        }
        return $services;
    }

    protected function makeConditions($confStates){
        $states = array();
        foreach($confStates as $state){
            $condition = new ObjectCondition($state["name"]);
            if(isset($state["on"])){
                foreach($state["on"] as $expression){
                    $condition->addRestriction($expression["status"],$expression["value"]);
                }
            }
            $states[] = $condition;
        }
        return $states;
    }

}

?>
