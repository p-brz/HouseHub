<?php

namespace househub\objects\parsers;

use househub\json\JsonData;
use househub\objects\ObjectVisualName;
use househub\json\JsonObject;

class ObjectVisualNameJsonParser {

    public function parse($entity) {
        $json = new JsonObject();

        if (is_null($entity)) {
            return $json;
        } else if (!($entity instanceof ObjectVisualName)) {
            return $json;
        }

        $json->addElement(new JsonData("id", $entity->getId()));
        $json->addElement(new JsonData("name", $entity->getObjectName()));

        return $json;
    }

}
