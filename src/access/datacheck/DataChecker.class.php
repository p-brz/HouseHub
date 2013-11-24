<?php
namespace househub\access\datacheck;

class DataChecker{

	private $verifiers;
	private $messages;

	public function __construct(){
		$this->verifiers = array();
		$this->messages = array();
	}

	public function addVerifier(VerifierInterface $interface){
		
	}

	public function check($data){
		$isValid = true;
		foreach($this->verifiers as $verifier){
			
		}

		return $isValid;
	}

}

?>