<?php

require_once("fix_paths.php");
require_once("install_functions.inc.php");

$classes = launch();
dumpClasses($classes);
?>