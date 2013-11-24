<?php

namespace househub\scheme;

class SchemeLoader{
    private $schemeReaders;
    
    public function addSchemeReader(SchemeReader $reader){
        $this->schemeReaders[] = $reader;
    }
    //public function removeSchemeReader(SchemeReader $schemeReader){
        //FIXME: remover reader
    //}

    public function loadScheme($schemeName){
        foreach($this->schemeReaders as $reader){
            $scheme = $reader->getScheme($schemeName);
            if(!is_null($scheme)){
                return $scheme;
            }
        }
        return null;
    }

}

?>
