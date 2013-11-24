<?php

use househub\access\DatabaseConnector;
use househub\intents\home\HomeIntentManager;
use househub\intents\parsers\JsonToIntentParser;
use lightninghowl\utils\StrOpers;
use househub\intents\parsers\IntentStructureJsonParser;
error_reporting(E_ALL); 
ini_set('display_errors', '1');
require_once '../../../lightninghowl/utils/AutoLoader.class.php';

$driver = new PDO("mysql:host=localhost;dbname=househub_db", 'househub', 'senhahousehub');
$scheme = '{"configs":{"id":100, "type":"metalamp"}, "objects":[{"services":{"ligar":0, "desligar":0}, "status":{"ligada":0}, "configs":{"id":201, "type":"lamp", "scheme":"basicLamp"}},{"services":{"ligar":0, "desligar":0}, "status":{"ligada":0}, "configs":{"id":202, "type":"lamp", "scheme":"basicLamp"}},{"services":{"ligar":0, "desligar":0}, "status":{"ligada":0}, "configs":{"id":203, "type":"lamp", "scheme":"basicLamp"}}]}';

$intentParser = new JsonToIntentParser();

$schemeJson = json_decode($scheme, true);
$intent = $intentParser->parse($schemeJson, '192.168.1.156');

$manager = new HomeIntentManager();
$intent = $manager->saveIntent($intent, DatabaseConnector::getDriver());

StrOpers::varFancyDump($intent);
?>