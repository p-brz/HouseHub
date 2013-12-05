<?php

namespace househub\objects\home;

use househub\abstracts\AbstractHomeJsonParser;
use househub\json\JsonData;
use househub\json\JsonObject;
use househub\objects\parsers\ObjectStructureJsonParser;
use househub\objects\parsers\ObjectVisualNameJsonParser;
use househub\readers\SchemeDictionary;
use househub\services\parsers\ServiceStructureJsonParser;
use househub\status\parsers\StatusStructureJsonParser;

class HomeObjectJsonParser extends AbstractHomeJsonParser {

    public function parse(HomeObject $object, $withSubObjects = false) {
        $this->json = new JsonObject();

        $this->parseObjectStructure($object, $this->json);

        $this->parseObjectVisual($object, $this->json);

        $this->parseServices($object, $this->json);
        $this->parseStatus($object, $this->json);

        // The sub objects (if needed)
        if ($withSubObjects) {
            $this->parseSubobjects($object, $this->json);
        }

        return $this->json;
    }

    protected function parseObjectVisual($object, $jsonParent) {
        // Now to the visual aspects
        $visualJson = new JsonObject("visual");

        $this->parseVisualName($object, $visualJson);

        // We'll need this little boy later
        //FIXME: objeto terá apenas 1 condição válida
        $conditions = $object->getValidConditions();
        $currentCondition = (is_array($conditions) && !empty($conditions)) ? $conditions[0] : null;
        
        //TODO:excluir ou não parseVisualIconPack??
        //$this->parseVisualIconPack($object,$visualJson,$currentCondition);	   
        //FIXME: condition não é algo apenas visual
        $this->parseCurrentCondition($object, $visualJson, $currentCondition);

        $jsonParent->addElement($visualJson);
    }

    protected function parseSubobjects($object, $jsonParent) {
        $subObjects = $object->getSubObjects();
        $subObjectsArray = $this->buildJsonArray("objects", $subObjects, new HomeObjectJsonParser());
        $jsonParent->addElement($subObjectsArray);
    }

    protected function parseStatus($object, $jsonParent) {
        // The status (there will be more than 1)
        $statusArray = $object->getStatus();
        $statusParser = new StatusStructureJsonParser();
        $serviceJsonArray = $this->buildJsonArray("status", $statusArray, $statusParser);
        $jsonParent->addElement($serviceJsonArray);
    }

    protected function parseServices($object, $jsonParent) {
        // The services (there will be more than 1)
        $services = $object->getServices();
        $serviceParser = new ServiceStructureJsonParser();
        $serviceJsonArray = $this->buildJsonArray("services", $services, $serviceParser);
        $jsonParent->addElement($serviceJsonArray);
    }

    protected function parseObjectStructure($object, $jsonParent) {
        // Loading the object structure and adding it to the json
        $structure = $object->getStructure();
        $structureParser = new ObjectStructureJsonParser();
        $structureJson = $structureParser->parse($structure);
        $jsonParent->addElement($structureJson);
        $jsonParent->addElement(new JsonData("schemeName", $structure->getSchemeName()));
    }

    protected function parseCurrentCondition($object, $parentJson, $currentCondition) {
        if(!is_null($currentCondition) &&!is_null($object->getScheme())) {
            $schDic = new SchemeDictionary($object->getScheme());
            $state = $schDic->translate($currentCondition->getName());

            $parentJson->addElement(new JsonData("condition", $state));
        } else {
            $parentJson->addElement(new JsonData("condition", null));
        }
    }

    protected function parseVisualName($object, $visualJson) {
        // First, the name
        $visualName = $object->getVisualName();
        $visualNameParser = new ObjectVisualNameJsonParser();
        $visualNameJson = $visualNameParser->parse($visualName);

        $setted = false;
        foreach ($visualNameJson->getElements() as $element) {
            if ($element->getName() == 'name') {
                $visualJson->addElement($element);
                $setted = true;
            }
        }
        //FIXME: isso ta certo??
        if (!$setted) {
            $visualJson->addElement(new JsonData("name", ""));
        }
    }

    /**
     * TODO:
     */
    protected function parseVisualIconPack($object, $visualJson, $currentCondition) {
        // Now to the iconpack
        $visualIconpack = $object->getVisualIconpack();
        $visualIconpackParser = new ObjectVisualIconpackJsonParser();
        $visualIconpackJson = $visualIconpackParser->parse($visualIconpack, $currentCondition);

        $setted = false;
        foreach ($visualIconpackJson->getElements() as $element) {
            if ($element->getName() == 'image') {
                $visualJson->addElement($element);
                $setted = true;
            }
        }

        if (!$setted) {
            $visualJson->addElement(new JsonData("image", null));
        }
    }

}
