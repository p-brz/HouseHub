<?php
// COMPLETE!!!
namespace househub\access\strategies\object;


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
use lightninghowl\utils\StrOpers;

use lightninghowl\utils\url\UrlHandler;

use househub\objects\ObjectStructure;

use househub\objects\dao\ObjectStructureDAO;

use lightninghowl\utils\encrypting\Base64Reverse;

use househub\users\rights\UserViews;

use househub\objects\home\HomeObjectManager;

use househub\users\session\SessionManager;

use househub\access\DatabaseConnector;

use househub\access\strategies\AbstractAccessStrategy;

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
		// Initialize the default structures
		$answer = $this->initializeAnswer();
		$driver = DatabaseConnector::getDriver();
		
		// Calls the session manager
		$sessManager = SessionManager::getInstance();
		$sessManager->startSession();
		$userId = $sessManager->getSessionVariable('user_id');
		// Verifies if the user is really authenticated
		if(is_null($userId)){
			$answer->setMessage('@user_needs_login');
		}else{
			// Load the objects allowed to the user
			$permission = new UserViews($userId, $driver);
			if(isset($parameters['object'])){
				$objectId = $parameters['object'];
			}else{
				$objectId = null;
			}
			
			if(isset($parameters['service'])){
				$decrypter = new Base64Reverse();
				$serviceId = $decrypter->decrypt(urldecode($parameters['service']));
			}else{
				$serviceId = null;
			}

			// Verifies if the parameters are all okay
			if(is_null($objectId) || is_null($serviceId)){
				$answer->setMessage('@bad_parameters');
			
			// Check the user permissions
			}else if(!$permission->verifyRights($objectId)){
				$answer->setMessage('@forbidden');
			}else{
				
				// Loads the object
				$manager = new HomeObjectManager();
				$obj = $manager->loadStructure($objectId, $driver);
				$services = $manager->loadServices($objectId, $driver);
				
				$name = '';
				foreach($services as $service){
					if($service->getId() == $serviceId){
						$name = $service->getName();
					}
				}
				
				if(empty($name)){
					$answer->setMessage('@service_not_found');
				}else if($obj->getConnected() == 0){
					$answer->setMessage('@object_is_offline');
					
				}else{
					// Call the service
					$addr = $obj->getAddress().'/services/'.$name;
					$handler = new UrlHandler('http://'.$addr, 'POST');
					$handler->setTimeout(5);
					$handler->run();
					
					if($handler->getStatus() == 404){
						$structure = $manager->loadStructure($objectId, $driver);
						$structure->setConnected(0);
						$dao = new ObjectStructureDAO($driver);
						$dao->update($structure);
						
						$answer->setMessage('@offline_object');
					}else if($handler->getStatus() == 200){
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