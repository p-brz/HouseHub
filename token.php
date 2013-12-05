<?php

// During our development meeting on PAIRG's laboratory, my friend Paulo was setting up
// our private server and then shout: "One of my tasks will be configure the configurations".
// We are very glad you read it actually


use househub\access\Launcher;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('upload_tmp_dir', 'files/tmp');
require_once('etc' . DIRECTORY_SEPARATOR . 'autoload.php');

$parameters = array();
if ($_POST) {
    $parameters = $_POST;
} else if ($_GET) {
    $parameters = $_GET;
}

$launcher = new Launcher();
$launcher->launch($parameters);
