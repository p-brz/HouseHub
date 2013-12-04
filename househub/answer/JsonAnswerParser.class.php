<?php

namespace househub\answer;

use househub\json\JsonData;

use househub\readers\LanguageReader;

use househub\json\JsonObject;

class JsonAnswerParser{
	
	public function answerToJson(AnswerEntity $answer){
		$jsonAnswer = new JsonObject();
		$language = LanguageReader::getInstance();
		
		$jsonAnswer->addElement(new JsonData("status", $answer->getStatus()));
		$jsonAnswer->addElement(new JsonData("message", $language->translate($answer->getMessage())));
		
		$json = $answer->getContent();
		$json->setName("content");
		$jsonAnswer->addElement($json);
		
		return $jsonAnswer;
	}
	
}

?>