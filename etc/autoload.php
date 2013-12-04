<?php

function file_autoload($className) {
    while (!file_exists(getcwd() . "/.htroot")) {
        chdir('..');
    }

    $packages = array(
        ''
    );

    $extensions = array(
        '.class.php',
        '.interface.php',
        '.php'
    );

    $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $className);


    foreach ($extensions as $extension) {
        foreach ($packages as $package) {
            $includePath = getcwd() . DIRECTORY_SEPARATOR . $package . $filePath . $extension;
            if (is_file($includePath)) {
                include_once $includePath;
                break;
            }
        }
    }
}

spl_autoload_register("file_autoload");

