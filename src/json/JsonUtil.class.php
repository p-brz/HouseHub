<?php

namespace househub\json;

class JsonUtil{
	public static function parseValueToString($value){
        $stringJson = '';
        if(is_null($value)){
            $stringJson = "null";
        }
        else if(is_bool($value)){
            //$stringJson = ($value ? "true" : "false");
            $stringJson = ($value ? "1" : "0");
        }
        else if(is_array($value)){
            $stringJson = "[ ";
            if(!empty($value)){
                $stringJson .= JsonUtil.parseValueToString($value[0]);
		        for($i =1; $i < count($value); $i++){
                    $stringJson .= ", ";
                    $stringJson .= JsonUtil.parseValueToString($value[$i]);
                }
            }
            $stringJson .= " ]";
        }
        else{
            $stringJson = "\"" . (string)$value . "\"";
            //$stringJson = ''. $value;
        }

        return $stringJson;
    }
}

?>
