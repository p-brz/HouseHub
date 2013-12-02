<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(php_sapi_name() == 'cli'){
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'receiver'. DIRECTORY_SEPARATOR . 'installer.php';
}
else{
    header("Location: ./index.php");
}


?>