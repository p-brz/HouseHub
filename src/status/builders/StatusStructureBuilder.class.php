<?php

namespace househub\status\builders;

use househub\status\tables\StatusStructureTable;
use househub\status\StatusStructure;

while(!file_exists(getcwd()."/.htroot")){chdir('..');}
require_once 'lightninghowl/utils/AutoLoader.class.php';

class StatusStructureBuilder {

    public function build($resource) {
        $status = new StatusStructure();

        if (isset($resource[StatusStructureTable::COLUMN_ID])) {
            $status->setId($resource[StatusStructureTable::COLUMN_ID]);
        }
        if (isset($resource[StatusStructureTable::COLUMN_NAME])) {
            $status->setName($resource[StatusStructureTable::COLUMN_NAME]);
        }
        if (isset($resource[StatusStructureTable::COLUMN_OBJECT_ID])) {
            $status->setObjectId($resource[StatusStructureTable::COLUMN_OBJECT_ID]);
        }
        if (isset($resource[StatusStructureTable::COLUMN_VALUE])) {
            $status->setValue($resource[StatusStructureTable::COLUMN_VALUE]);
        }

        return $status;
    }

}

?>