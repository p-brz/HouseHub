<?php

namespace househub\readers;

use Exception;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'HubConf.class.php';

final class SystemReader extends Dictionary {

    const INDEX_ROOTPATH = 'project_root';
    const INDEX_SCHEMES = 'schemes_path';
    const INDEX_CONFIGS = 'configs_path';
    const INDEX_DATABASE = 'database_path';
    const INDEX_LANGUAGES = 'language_path';
    const INDEX_PACKS = 'iconpacks_path';
    const INDEX_UPLOADS = 'uploads_path';
    const INDEX_SYSLANG = 'system_language';
    const INDEX_DBCONF = 'database_file';

    private function __construct() {
        $systemRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $setup = $systemRoot . 'setup.ini';
        if (is_file($setup)) {
            $settings = parse_ini_file($setup);
            $sysRes = array_merge(\HubConf::getConfigurations(), $settings);

            $this->setSource($sysRes);
        } else {

            throw new Exception('File "setup.ini" not found.');
        }
    }

    // Singleton
    private static $instance;

    public static function getInstance($forceRebuild = false) {
        if (!self::$instance || $forceRebuild) {
            self::$instance = new SystemReader();
        }

        return self::$instance;
    }

}