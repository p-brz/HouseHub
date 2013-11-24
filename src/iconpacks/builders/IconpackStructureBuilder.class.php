<?php

namespace househub\iconpacks\builders;

use lightninghowl\reader\JsonReader;

use lightninghowl\utils\StrOpers;

use househub\readers\SystemReader;

use househub\iconpacks\tables\IconpackStructureTable;

use househub\iconpacks\IconpackStructure;

class IconpackStructureBuilder{
	
	public function build($resource){
		$iconpack = new IconpackStructure();
		
		if(isset($resource[IconpackStructureTable::COLUMN_ID])) $iconpack->setId($resource[IconpackStructureTable::COLUMN_ID]);
		if(isset($resource[IconpackStructureTable::COLUMN_NAME])) $iconpack->setName($resource[IconpackStructureTable::COLUMN_NAME]);
		if(isset($resource[IconpackStructureTable::COLUMN_FOLDER])) $iconpack->setFolder($resource[IconpackStructureTable::COLUMN_FOLDER]);
		if(isset($resource[IconpackStructureTable::COLUMN_TARGET])) $iconpack->setTarget($resource[IconpackStructureTable::COLUMN_TARGET]);
		if(isset($resource[IconpackStructureTable::COLUMN_ENTRY_DATE])) $iconpack->setEntryDate($resource[IconpackStructureTable::COLUMN_ENTRY_DATE]);
		
		if(!is_null($iconpack->getFolder())){
			$iconpack->setDictionary($this->buildDictionary($iconpack->getFolder()));
		}
		
		return $iconpack;
	}
	
	private function buildDictionary($folder){
		// First, we must verify where exactly the set is
		$sysRes = SystemReader::getInstance();
		
		// Now we save this path
		$path = $_SERVER['DOCUMENT_ROOT'].'/'.$sysRes->translate(SystemReader::INDEX_ROOTPATH);
		$iconLocale = $sysRes->translate(SystemReader::INDEX_PACKS);
		$iconLocale .= '/'.$folder;
		$path = StrOpers::strFixPath($path.'/'.$iconLocale);
		
		// Since we have found the pack, we simply set up the dictionary
		$manifest = $path.DIRECTORY_SEPARATOR.'manifest.json';
		
		$reader = new JsonReader($manifest);
		
		$dictionary = array();
		$infos = $reader->get('dictionary');
		
		// Fix directory dependances
		foreach($infos as $key=>$meaning){
			$locale = $iconLocale.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.$meaning;
			$dictionary[$key] = StrOpers::strWebPath($locale);
		}
		
		return $dictionary;
	}
	
}

?>