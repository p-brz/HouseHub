<?php

class HubConf {

    private static $configurations = null;

    private function __construct() {
        
    }

    public static function getConfigurations() {
        if (!isset(self::$configurations) || is_null(self::$configurations)) {
            $projectRoot = dirname(__FILE__);
            $configurations = array(
                'project_root' => $projectRoot,
                'manifest_path' => 'manifest',
                'uploads_path' => "files/uploads",
                'schemes_path' => 'files/schemes',
                'configs_path' => 'files/configs',
                'database_path' => 'files/configs',
                'language_path' => 'files/language',
                'iconpacks_path' => 'files/iconpacks'
            );

            self::$configurations = array();
            foreach ($configurations as $index => $configuration) {
                $string = str_replace('/', DIRECTORY_SEPARATOR, $configuration);
                self::$configurations[$index] = str_replace('\\', DIRECTORY_SEPARATOR, $string);
            }
        }
        return self::$configurations;
    }

}
