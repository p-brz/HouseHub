<?php

namespace househub\readers;

use lightninghowl\utils\StrOpers;

use househub\scheme\Scheme;

use lightninghowl\reader\JsonReader;

class SchemeDictionary extends Dictionary{
	
	public function __construct(Scheme $scheme){
		$sysRes = SystemReader::getInstance();
		$schemePath = $sysRes->translate(SystemReader::INDEX_ROOTPATH).'/'.$sysRes->translate(SystemReader::INDEX_SCHEMES);
		$systemLanguage = $sysRes->translate(SystemReader::INDEX_SYSLANG);
		
		$schemeLanguage = $scheme->getSchemeName().'Language.json';
		
		$file = $_SERVER['DOCUMENT_ROOT'].'/'.$schemePath.'/'.$schemeLanguage;
		$languageReader = new JsonReader(StrOpers::strFixPath($file));
		
		$arrayLanguage = $languageReader->get($systemLanguage);
		$this->setSource($arrayLanguage);
	}
	
}

?>