<?php

require_once("fix_paths.php");
require_once("install_functions.inc.php");

$classes = launch();

if(php_sapi_name() != 'cli'){
    dumpClasses($classes);
}
else{
//    var_dump($classes);
    dumpClassesCli($classes);
}