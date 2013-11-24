<?php

namespace Core\Access\Strategies\Object;

use Core\Access\Strategies\AbstractAccessStrategy;

use Core\User\SessionManager;

use Core\User\Rights\UserViews;

use Core\Answer\JsonAnswerParser;

use Core\Answer\AnswerEntity;

use Core\Access\DatabaseConnector;

use Core\Loaders\ObjectLoader;

use LightningHowl\Utils\StrOpers;

use Core\Reader\SystemReader;

use LightningHowl\Reader\JsonReader;
use LightningHowl\Utils\Url\UrlHandler;
use Core\DAO\BlockObjectDAO;
use LightningHowl\Database\DbDriver;
use LightningHowl\Database\Arguments\MySQLArgument;
use Exception;

/**
 * Call a service from a given object
 * 
 * Estratégia responsável por chamar um serviço disponível em um dado objeto.
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.2.0
 *
 */
class CallServiceAccessStrategy extends AbstractAccessStrategy{
	
	/**
	 * Ask for the service to be accomplished
	 * 
	 * Realiza a chamada ao serviço. O array 'parameters' deve conter duas chaves:
	 * 1. objid - Id do objeto
	 * 2. service - id do serviço
	 * 
	 * @see uCore/Access/Strategies/AbstractAccessStrategy::requestAccess()
	 * @param array $parameters The request parameters.
	 */
	public function requestAccess($parameters){
		$answer = new AnswerEntity();
		$answer->setStatus(0);
		$answer->setMessage('@error');
		
		$driver = DatabaseConnector::getDriver();
		
		
		$sessManager = SessionManager::getInstance();
		$sessManager->startSession();
		$userId = $sessManager->getSessionVariable('user_id');
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			$permission = new UserViews($userId, $driver);
		
			if(isset($parameters['objid'])){
				$objectId = $parameters['objid'];
			}else{
				$objectId = null;
			}
			
			if(isset($parameters['service'])){
				$serviceId = $parameters['service'];
			}else{
				$serviceId = null;
			}
			
			if(is_null($objectId) || is_null($serviceId)){
				$answer->setMessage('@bad_parameters');
			}else if(!$permission->verifyRights($objectId)){
				$answer->setMessage('@forbidden');
			}else{
				$loader = new ObjectLoader();
				
				$obj = $loader->load($objectId, $driver);
				$services = $obj->getBlockServices();
				
				$name = '';
				foreach($services as $service){
					if($service->getId() == $serviceId){
						$name = $service->getBlockServiceName();
					}
				}
				
				if(empty($name)){
					$answer->setMessage('@service_not_found');
				}else if($obj->getConnected() == 0){
					$answer->setMessage('@object_is_offline');
					
				}else{
					$addr = $obj->getAddr().'/services/'.$name;
					$handler = new UrlHandler($addr, 'POST');
					$handler->setTimeout(5);
					$handler->run();
					
					if($handler->getStatus() == 404){
						$obj->setConnected(0);
						$dao = new BlockObjectDAO($driver);
						$dao->update($obj);
						
						$answer->setMessage('@offline_object');
					}else{
						$answer->setStatus(1);
						$answer->setMessage('@success');
					}
					
				}
			}
		}
		
		return $answer;
		
	}
	
}

?>