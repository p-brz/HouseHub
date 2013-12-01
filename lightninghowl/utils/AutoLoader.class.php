<?php

namespace lightninghowl\utils;

require_once __DIR__ . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'HubConf.class.php';

    use Exception;
    
class AutoLoader {

    private static $classAssocArray;

    
    public static function autoLoad($className) {
        echo "AutoLoader::autoLoad(".$className.")\n";
        
        
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
            
            var_dump(self::$classAssocArray);
        }
        $classPath = isset(self::$classAssocArray[$className]) ? self::$classAssocArray[$className] : null;

        if (!is_null($classPath)) {
            require_once($classPath);
        } else {
            echo 'Class ' . $className . ' not found';
            throw  new Exception("Not found Class " . $className);
        }
    }

    private static function fixPath($value) {
        $string = str_replace('\\', DIRECTORY_SEPARATOR, $value);

        return str_replace('/', DIRECTORY_SEPARATOR, $string);
    }

}

//spl_autoload_register(array('lightninghowl\utils\AutoLoader', 'autoLoad'));
//spl_autoload_register(array('lightninghowl\utils\AutoLoader', 'autoLoad'),true);
spl_autoload_register(array('lightninghowl\utils\AutoLoader', 'autoLoad'));
