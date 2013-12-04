<?php

$d = DIRECTORY_SEPARATOR;
require_once __DIR__ . $d . '..'. $d . '..'. $d . 'HubConf.class.php';

function fixSeparator($path) {
    return str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $path));
}

/**
 * 
 * @return algo
 */
function manifestDetails() {

    // Carregando as diretrizes
    $d = DIRECTORY_SEPARATOR;
    $configFile = dirname(__FILE__) . $d . 'config' . $d . 'directives.json';
    $hubConfigs = HubConf::getConfigurations();
    
    if (is_file($configFile) && !is_null($hubConfigs)) {
        $directives = json_decode(file_get_contents($configFile), true);

        $projectRoot = $hubConfigs['project_root'];
        $output = $projectRoot . $d . ($hubConfigs['manifest_path']);

        $codePacks = $directives['contents'];
        return array('root' => $projectRoot, 'output' => $output, 'codePacks' => $codePacks);
    } else {
        echo 'Erro';
        exit(0);
    }
}
