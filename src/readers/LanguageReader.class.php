<?php

namespace househub\readers;

use lightninghowl\utils\StrOpers;
use lightninghowl\reader\JsonReader;

while (!file_exists(getcwd() . "/.htroot")) {
    chdir('..');
}
require_once 'lightninghowl/utils/AutoLoader.class.php';

final class LanguageReader extends Dictionary {

    private static $instance;

    private function __construct() {
        $sysReader = SystemReader::getInstance();
        $languagePath = $sysReader->translate(SystemReader::INDEX_ROOTPATH) . '/' . $sysReader->translate(SystemReader::INDEX_LANGUAGES);
        $language = $sysReader->translate(SystemReader::INDEX_SYSLANG);

        $filepath = StrOpers::strFixPath(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/' . $languagePath . '/' . $language . '.json');
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