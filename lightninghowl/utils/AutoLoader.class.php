<?php

namespace lightninghowl\utils;

class AutoLoader {
    private static $classAssocArray;
    private static $manifestLocation;

    public static function autoLoad($className) {
        if (is_null(self::$manifestLocation)) {
            $iniFile = parse_ini_file(self::fixPath(dirname(__FILE__) . '/../../hub.ini'));
            self::$manifestLocation = self::fixPath($iniFile['projectRoot'] . '/' . $iniFile['manifestPath']);
        }

        if (is_null(self::$classAssocArray)) {
            $path = self::fixPath(self::$manifestLocation);
            $serverRoot = $_SERVER['DOCUMENT_ROOT'];
            $manifestLocation = $serverRoot . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . 'classManifest.json';
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
