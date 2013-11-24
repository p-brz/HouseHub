<?php
namespace househub\intents\home;

use househub\abstracts\AbstractHomeJsonParser;

use househub\intents\parsers\IntentStructureJsonParser;

use househub\json\JsonObject;

class HomeIntentJsonParser extends AbstractHomeJsonParser{
	
	public function parse(HomeIntent $intent, $withSubObjects = false){
		$this->json = new JsonObject();
		
		$structure = $intent->getStructure();
		$structureParser = new IntentStructureJsonParser();
		$structureJson = $structureParser->parse($structure);
		$this->addElements($structureJson);
		
		if($withSubObjects){
			$intentArray = $intent->getSubIntents();
			$jsonArray = $this->buildJsonArray("sub_intents", $intentArray, new HomeIntentJsonParser());
			$this->json->addElement($jsonArray);
		}
		
		return $this->json;
	}
	
}