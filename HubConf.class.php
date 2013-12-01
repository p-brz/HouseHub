<?php

class HubConf {

//    private static $configurations = array(
//        'project_root' => __DIR__,
//        'manifest_path' => 'manifest',
//        'uploads_path' => 'files\\uploads',
//        'schemes_path' => 'files\\schemes',
//        'configs_path' => 'files\\configs',
//        'database_path' => 'files\\configs',
//        'language_path' => 'files\\language',
//        'iconpacks_path' => 'files\\iconpacks'
//    );
    private static $configurations = null;

    private function __construct() {}
    
    public static function getConfigurations(){
        if(self::$configurations == null){
            $arr =  array(
                'project_root' => __DIR__,
                'manifest_path' => 'manifest',
                'uploads_path' => 'files' . DIRECTORY_SEPARATOR .'uploads',
                'schemes_path' => 'files' . DIRECTORY_SEPARATOR .'schemes',
                'configs_path' => 'files' . DIRECTORY_SEPARATOR .'configs',
                'database_path' => 'files' . DIRECTORY_SEPARATOR .'configs',
                'language_path' => 'files' . DIRECTORY_SEPARATOR .'language',
                'iconpacks_path' => 'files' . DIRECTORY_SEPARATOR .'iconpacks'
            );
            self::$configurations = $arr;
            
//            echo 'SET configurations to: '."\n" . $arr;
        }
        return self::$configurations;
    }
}
