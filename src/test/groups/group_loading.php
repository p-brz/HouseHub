<?php
use househub\groups\home\HomeGroupJsonParser;
use househub\groups\home\HomeGroupManager;
use lightninghowl\utils\StrOpers;
use househub\groups\dao\GroupStructureDAO;
use househub\groups\GroupStructure;

error_reporting(E_ALL); 
ini_set('display_errors', '1');
require_once '../../../lightninghowl/utils/AutoLoader.class.php';


$driver = new PDO("mysql:host=localhost;dbname=househub_db", 'househub', 'senhahousehub');
$manager = new HomeGroupManager();
$homeGroup = $manager->loadGroup(4, 1, $driver);

StrOpers::varFancyDump($homeGroup);
$parser = new HomeGroupJsonParser();

$json = $parser->parse($homeGroup);
echo $json->valueToString();

?>