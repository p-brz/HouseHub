<?php

class HubConf {

    private static $configurations = array(
        'project_root' => __DIR__,
        'manifest_path' => 'manifest',
        'uploads_path' => 'files\\uploads',
        'schemes_path' => 'files\\schemes',
        'configs_path' => 'files\\configs',
        'database_path' => 'files\\configs',
        'language_path' => 'files\\language',
        'iconpacks_path' => 'files\\iconpacks'
    );

    private function __construct() {}
    
    public static function getConfigurations(){
        return DefaultConfigurations::$configurations;
    }
}
