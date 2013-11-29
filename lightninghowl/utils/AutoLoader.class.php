<?php

namespace lightninghowl\utils;

require_once \realpath(__DIR__ . '..\\..\\..') . DIRECTORY_SEPARATOR . 'HubConf.class.php';

class AutoLoader {

    private static $classAssocArray;

    public static function autoLoad($className) {
        $configurations = \HubConf::getConfigurations();
        $projectRoot = $configurations['project_root'];

        $manifestLocation = '';
        $manifestLocation .= $projectRoot;
        $manifestLocation .= DIRECTORY_SEPARATOR;
        $manifestLocation .= $configurations['manifest_path'];

        if (is_null(self::$classAssocArray)) {
            $manifestLocation = self::fixPath($manifestLocation) . DIRECTORY_SEPARATOR . 'classManifest.json';
            $configText = file_get_contents($manifestLocation);
            self::$classAssocArray = json_decode($configText, true);
        }
        $classPath = isset(self::$classAssocArray[$className]) ? self::$classAssocArray[$className] : null;

        if (!is_null($classPath)) {
            require_once($classPath);
        } else {
            echo 'Class ' . $className . ' not found';
        }
    }

    private static function fixPath($value) {
        $string = str_replace('\\', DIRECTORY_SEPARATOR, $value);

        return str_replace('/', DIRECTORY_SEPARATOR, $string);
    }

}

spl_autoload_register(array('lightninghowl\utils\AutoLoader', 'autoLoad'));
