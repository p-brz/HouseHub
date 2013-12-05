<?php

namespace househub\access\strategies\files;

use househub\access\DatabaseConnector;
use househub\access\strategies\AbstractAccessStrategy;
use househub\images\dao\ImageStructureDAO;
use househub\images\parsers\ImageToJsonParser;
use househub\json\JsonArray;
use househub\users\rights\UserImages;
use househub\users\session\SessionManager;

class ImageGatheringAccessStrategy extends AbstractAccessStrategy {

    private $dbDriver;

    public function __construct($driver = null) {
        $this->dbDriver = (!is_null($driver)? $driver : DatabaseConnector::getDriver());
    }
    
    public function requestAccess($parameters) {
        $answer = $this->initializeAnswer();

        $sessManager = SessionManager::getInstance();

        $userId = $sessManager->getSessionVariable('user_id');
        if (is_null($userId)) {
            $answer->setMessage('@user_needs_login');
        } else {
            $driver = $this->dbDriver;
            $permissions = new UserImages($userId, $driver);

            $imgParser = new ImageToJsonParser();
            $imgDao = new ImageStructureDAO($driver);

            $images = new JsonArray("images");
            foreach ($permissions->getRights() as $image) {
                $actImage = $imgDao->load($image);
                $jsonImage = $imgParser->imageToJson($actImage);
                $images->addElement($jsonImage);
            }

            $answer->setStatus(1);
            $answer->setMessage('@success');
            $answer->setContent($images);
        }

        return $answer;
    }

}

?>