<?php

namespace househub\groups;

/**
 * The group main structure.
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0
 */
class GroupStructure{
	
	/**
	 * The group id
	 * @var integer
	 */
	private $id;
	
	/**	
	 * The creator id
	 * @var integer
	 */
	private $userId;
	
	/**
	 * The registration date
	 * @var string
	 */
	private $registrationDate;
	
	public function getId() { return $this->id; } 
	public function getUserId() { return $this->userId; } 
	public function getRegistrationDate() { return $this->registrationDate; } 

	public function setId($x) { $this->id = $x; } 
	public function setUserId($x) { $this->userId = $x; }
	public function setRegistrationDate($x) { $this->registrationDate = $x; } 
}

?>