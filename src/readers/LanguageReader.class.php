<?php

namespace househub\readers;

use lightninghowl\utils\StrOpers;
use lightninghowl\reader\JsonReader;

final class LanguageReader extends Dictionary {

    private static $instance;

    private function __construct() {
        $sysReader = SystemReader::getInstance();
        $languagePath = $sysReader->translate(SystemReader::INDEX_ROOTPATH) . '/' . $sysReader->translate(SystemReader::INDEX_LANGUAGES);
        $language = $sysReader->translate(SystemReader::INDEX_SYSLANG);

        $languageFile = StrOpers::strFixPath($languagePath . '/' . $language . '.json');
        $filepath = StrOpers::strFixPath($languageFile);
        $jsonReader = new JsonReader($filepath);
        $this->setSource($jsonReader->get());
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new LanguageReader();
        }

        return self::$instance;
    }

}