<?php

namespace househub\objects\parsers;

use househub\json\JsonData;
use househub\objects\ObjectStructure;
use househub\json\JsonObject;

class ObjectStructureJsonParser {

    public function parse($entity) {
        $json = new JsonObject();

        if (is_null($entity)) {
            return $json;
        } else if (!($entity instanceof ObjectStructure)) {
            return $json;
        }

        $json->addElement(new JsonData("kind", 'BlockObject'));
        $json->addElement(new JsonData("id", $entity->getId()));
        $json->addElement(new JsonData("type", $entity->getType()));
        $json->addElement(new JsonData("reg_time", $entity->getRegistrationDate()));
        $json->addElement(new JsonData("validated", $entity->getValidated()));
        $json->addElement(new JsonData("connected", $entity->getConnected()));

        return $json;
    }

}

?>