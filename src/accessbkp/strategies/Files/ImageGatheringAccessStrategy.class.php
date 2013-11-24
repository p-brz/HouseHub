<?php
namespace Core\Access\Strategies\Files;

use Core\Json\JsonArray;

use Core\Images\BlockImageDAO;

use Core\Json\JsonObject;

use Core\Images\ImageToJsonParser;

use Core\Access\DatabaseConnector;

use Core\User\Rights\UserImages;

use Core\User\SessionManager;

use Core\Answer\JsonAnswerParser;

use Core\Access\Strategies\AbstractAccessStrategy;


class ImageGatheringAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = $this->initializeAnswer();
		
		
		$sessManager = SessionManager::getInstance();
		$userId = $sessManager->getSessionVariable('user_id');
		$userId = 1;
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$driver = DatabaseConnector::getDriver();
			$permissions = new UserImages($userId, $driver);
			$imgParser = new ImageToJsonParser();
			$imgDao = new BlockImageDAO($driver);
			$images = new JsonArray("images");
			foreach($permissions->getRights() as $image){
				$actImage = $imgDao->getBlockImage($image);
				$jsonImage = $imgParser->imageToJson($actImage);
				$images->addElement($jsonImage);
			}
			
			$answer->setStatus(1);
			$answer->setMessage('@success');
			$answer->addContent($images);
		}
		
		return $answer;
		
	}
	
}

?>