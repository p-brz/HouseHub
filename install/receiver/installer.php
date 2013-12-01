<?php

use lightninghowl\utils\sql\SqlCriteria;
use lightninghowl\utils\sql\SqlFilter;
use househub\users\tables\UserStructureTable;
use lightninghowl\utils\sql\UpdateQuery;
use househub\users\UserStructure;
use househub\users\dao\UserStructureDAO;
use househub\access\DatabaseConnector;
use lightninghowl\utils\StrOpers;
use househub\readers\SystemReader;
use househub\json\JsonData;
use househub\json\JsonObject;
$d = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__).$d.'..'.$d.'..'.$d.'lightninghowl'.$d.'utils'.$d.'AutoLoader.class.php');
require_once(dirname(__FILE__).$d.'..'.$d.'manifest'.$d.'fix_paths.php');
$classes = launch();

error_reporting(E_ALL);
ini_set('display_errors', '1');




if(php_sapi_name() == 'cli'){
    $options = array("db-user:", "db-pass:","db-name:","adm_user:","adm_pass:","db-port::");
    $args = getopt("", $options);
    install($args);
}
else{
    //$args = $_POST;
    install($_POST);
}

function install($args){
        // var_dump($args);   
    $rootUser = $args['db-user'];
    $rootPass = $args['db-pass'];
    $database = $args['db-name'];

    $adminUser = $args['adm_user'];
    $adminPass = $args['adm_pass'];

    if(isset($args['db-port']) && !empty($args['db-port'])){
            $port =  $args['db-port'];	
    }else{
            $port = 3306;
    }

    // The DSN
    $dsn = "mysql:host=localhost;port=$port";


    try{
            $pdo = new PDO($dsn, $rootUser, $rootPass);

            createDatabase($database,$pdo);
            createUser($database,$pdo);
            createDatabaseStructure($database, $pdo);

            echo "Saving the access file \n";
            saveAccessFile($rootUser,$rootPass,$database,$port);
            updateSetupFile($database);

            // Reload the resources
            SystemReader::getInstance(true);

            echo "Adding the admin user \n";
            $createdAdmin = createAdminUser($adminUser,$adminPass);

            if($createdAdmin){
                header("Location: ../../install_complete.php");	
            }else{
                echo "Cannot hire admin \n";
            }
    }catch(Exception $e){
            echo $e->getMessage();
    }
}

function updateSetupFile($database){
    echo "Updating the file reference\n";
    $d = DIRECTORY_SEPARATOR;
//    $projectRoot = $_SERVER['DOCUMENT_ROOT'].$d.$sysRes->translate(SystemReader::INDEX_ROOTPATH);
    $configs = HubConf::getConfigurations();
    $projectRoot = $configs['project_root'];
    $setupFile = $projectRoot.$d.'setup.ini';
    $setup = parse_ini_file(StrOpers::strFixPath($setupFile));
    $setup[SystemReader::INDEX_DBCONF] = $database.'.json';

    write_ini_file($setup, $setupFile);
}

function createDatabaseStructure($database, $pdo){
    // The database script
    $d = DIRECTORY_SEPARATOR;
    $file = dirname(__FILE__)."{$d}..{$d}database{$d}database_structure.sql";
    $sqlInstruction = file_get_contents($file);

    $pdo->exec("USE {$database}");
    $pdo->exec($sqlInstruction);
}

function createAdminUser($adminUser, $adminPass){
    echo "Create User Admin \n";
    $driver = DatabaseConnector::getDriver();
    
    var_dump($driver);
    
    $userDAO = new UserStructureDAO($driver);
    
    $user = new UserStructure();
    $user->setName('Administrator');
    $user->setNickname('Administrator');
    $user->setGender('?');
    $user->setUsername($adminUser);
    $user->setPassword($adminPass);
    
    return saveUser($user, $userDAO, $driver);
}

function saveUser($user, $userDAO, $driver){
    echo "AFF\n";
    $userId = $userDAO->insert($user);
    $update = new UpdateQuery();
    $update->setEntity(UserStructureTable::TABLE_NAME);

    $update->setRowData(UserStructureTable::COLUMN_ID, 0);

    $criteria = new SqlCriteria();
    $criteria->add(new SqlFilter(UserStructureTable::COLUMN_ID, '=', $userId));
    $update->setCriteria($criteria);

    $modify = $driver->exec($update->getInstruction());
    
    return ($modify == 1);
}

function saveAccessFile($user, $pass, $database, $port){
    $d = DIRECTORY_SEPARATOR;
    
    $json = new JsonObject();
    $json->addElement(new JsonData("db_host", 'localhost'));
    $json->addElement(new JsonData("db_user", $user));
    $json->addElement(new JsonData("db_pass", $pass));
    $json->addElement(new JsonData("db_name", $database));
    $json->addElement(new JsonData("db_port", $port));
    $json->addElement(new JsonData("db_type", 'mysql'));

    $sysRes = SystemReader::getInstance();
    
    $configs = HubConf::getConfigurations();
    $projectRoot = $configs['project_root'];
    
    $savePath = $sysRes->translate(SystemReader::INDEX_DATABASE).$d.$database.'.json';
        
    $savePath = StrOpers::strFixPath($projectRoot.$d.$savePath);
    
    
    $content = $json->valueToString();
        
    file_put_contents($savePath, $content);
}

function createDatabase($database, $pdo){
    $query  = "CREATE DATABASE {$database} ";
    $query .= "DEFAULT CHARACTER SET utf8 ";
    $query .= "DEFAULT COLLATE utf8_general_ci; ";

    $pdo->exec($query);
}

function createUser($database, $pdo){
    $user = $database.'user';
    $pass = randomPassword(30);
    $userQuery  = "CREATE USER '{$user}'@'localhost' IDENTIFIED BY '$pass'; ";
    $userQuery .= "GRANT ALL ON `{$database}`.* TO '{$user}'@'localhost'; ";
    $userQuery .= "FLUSH PRIVILEGES;";
    
    $pdo->exec($userQuery);
}

function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            //TODO: extrair método (códigos duplicados)
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if ($elem2 == "") {
                    $content .= $key2 . " = \n";
                } else {
                    $content .= $key2 . " = \"" . $elem2 . "\"\n";
                }
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key2=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key2."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if ($elem == "") {
                $content .= $key2 . " = \n";
            } else {
                $content .= $key2 . " = \"" . $elem . "\"\n";
            }
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    } 
    if (!fwrite($handle, $content)) { 
        return false; 
    } 
    fclose($handle); 
    return true; 
}



function randomPassword($passLength) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < $passLength; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>