<?php
namespace househub\users\session;

use lightninghowl\utils\encrypting\Sha1Hash;

final class SessionManager{
	
	private static $instance;
	
	private function __construct(){}
	
	public static function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new SessionManager();
		}
		
		return self::$instance;
	}
	
	public function startSession(){
		if(!$this->isSessionStarted()){
			session_start();
		}
	}
	
	public function destroySession(){
		if(!$this->isSessionStarted()){
			session_start();
		}
		
		session_destroy();
		
		session_unset();
	}
	
	public function isSessionStarted(){
		return (session_id() != '');
	}
	
	public function getSessionVariable($varName){
		if(session_id() == '') session_start();
		
		$returnValue = null;
		
		$hash = new Sha1Hash();
		$index = $hash->encrypt($varName);
		
		if(isset($_SESSION[$index])){
			$returnValue = $_SESSION[$index];
		}
		
		return $returnValue;
	}
	
	public function setSessionVariable($varName, $varValue){
		if(session_id() == '') session_start();
		
		$hash = new Sha1Hash();
		$index = $hash->encrypt($varName);
		
		$_SESSION[$index] = $varValue;
	}
	
}