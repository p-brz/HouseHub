<?php

use househub\objects\home\HomeObjectManager;
use lightninghowl\utils\StrOpers;
use househub\objects\home\JsonToHomeObjectParser;
error_reporting(E_ALL); 
ini_set('display_errors', '1');
require_once '../../../lightninghowl/utils/AutoLoader.class.php';

$driver = new PDO("mysql:host=localhost;dbname=househub_db", 'househub', 'senhahousehub');
$manager = new HomeObjectManager();
$parser = new JsonToHomeObjectParser();

//$json = '{"services":{"travar":0, "destravar":0}, "status":{"aberta":1, "travada":0}, "configs":{"id":100, "type":"door", "scheme":"basicDoor"}}';
$json = '{"configs":{"id":100, "type":"metalamp"}, "objects":[{"services":{"ligar":0, "desligar":0}, "status":{"ligada":0}, "configs":{"id":201, "type":"lamp", "scheme":"basicLamp"}},{"services":{"ligar":0, "desligar":0}, "status":{"ligada":0}, "configs":{"id":202, "type":"lamp", "scheme":"basicLamp"}},{"services":{"ligar":0, "desligar":0}, "status":{"ligada":0}, "configs":{"id":203, "type":"lamp", "scheme":"basicLamp"}}]}';
$jsonEntity = json_decode($json, true);
$homeObject = $parser->parse($jsonEntity, '192.168.1.116');

$homeObject = $manager->saveObject($homeObject, $driver);
StrOpers::varFancyDump($homeObject);

?>