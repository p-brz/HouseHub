<?php

// Complete

namespace househub\access\strategies\object;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\objects\home\HomeObjectManager;
use lightninghowl\utils\url\UrlHandler;

// Atenção: Preciso modificar essa classe para validar o endereço de atualização
class UpdateStatusAccessStrategy extends AbstractAccessStrategy {

    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();
        $addr = $this->extractAddress($parameters);

        if(!is_null($addr)){
            $objJson = $this->getUpdatedStatus($addr);

            if (!is_null($objJson)) {
                $this->updateObjectStatus($objJson);
                
                $answer->setStatus(1);
            }
        }
        
        return $answer;
    }

    public function extractAddress($parameters) {
        // First, the submitted index
        $index = (int) (isset($parameters['obj_index']) ? $parameters['obj_index'] : -1);
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $request = $_SERVER['REMOTE_ADDR'];

            // Let us fix the address
            $addr = 'http://' . $request;
            if ($index > -1) {
                $addr .= '/objects/' . $index;
            }

            return $addr;
        } else {
            return null;
        }
    }

    public function getUpdatedStatus($addr) {
        // Call the page
        $handler = new UrlHandler($addr);
        $handler->run();

        // The json answer
        $objJson = $handler->getContent();

        return $objJson;
    }

    public function updateObjectStatus($objJson) {
        $wildObject = json_decode($objJson, true);
        $objId = $wildObject['configs']['id'];
        $objStatus = $wildObject['status'];
        $driver = DatabaseConnector::getDriver();

        $objManager = new HomeObjectManager();
        $realStatus = $objManager->loadStatus($objId, $driver);

        if (!is_null($realStatus) && !empty($realStatus)) {

            //FIXME
            throw new Exception("Not implemented correctly");

            foreach ($realStatus as $key => $singleStatus) {
                $name = $singleStatus->getName();
                $value = $objStatus[$name];
                //FIXME: nem todos os status precisam ser inteiros
                if (intval($value) != intval($singleStatus->getValue())) {
                    $singleStatus->setValue($value);
                    $stsDao->update($singleStatus);
                }
            }
        }
    }

}

?>