<?php

use househub\images\dao\ImageStructureDAO;
use lightninghowl\utils\StrOpers;
use househub\iconsets\dao\IconsetStructureDAO;
error_reporting(E_ALL); 
ini_set('display_errors', '1');
require_once '../../../lightninghowl/utils/AutoLoader.class.php';


$driver = new PDO("mysql:host=localhost;dbname=househub_db", 'househub', 'senhahousehub');
$imageDAO = new ImageStructureDAO($driver);
$icon = $imageDAO->load(1);

StrOpers::varFancyDump($icon);
?>