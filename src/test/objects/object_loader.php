<?php

use househub\objects\home\HomeObjectJsonParser;
use househub\objects\home\HomeObjectManager;
use lightninghowl\utils\StrOpers;
use househub\objects\facade\ObjectFacadeManager;
error_reporting(E_ALL); 
ini_set('display_errors', '1');
require_once '../../../lightninghowl/utils/AutoLoader.class.php';


$driver = new PDO("mysql:host=localhost;dbname=househub_db", 'househub', 'senhahousehub');
$manager = new HomeObjectManager();
$homeObject = $manager->loadObject(3, 1, $driver);

StrOpers::varFancyDump($homeObject);

$parser = new HomeObjectJsonParser();
$json = $parser->parse($homeObject, true);

echo $json->valueToString();

?>