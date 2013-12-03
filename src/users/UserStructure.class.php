<?php
namespace househub\users;

class UserStructure{
	
	private $id;
	private $name;
	private $nickname;
	private $gender;
	private $username;
	private $password;
	private $registrationDate;
	
	public function getId() { return $this->id; } 
	public function getName() { return $this->name; } 
	public function getNickname() { return $this->nickname; } 
	public function getGender() { return $this->gender; } 
	public function getUsername() { return $this->username; } 
	public function getPassword() { return $this->password; } 
	public function getRegistrationDate() { return $this->registrationDate; }
	
	public function setId($x) { $this->id = $x; } 
	public function setName($x) { $this->name = $x; } 
	public function setNickname($x) { $this->nickname = $x; } 
	public function setGender($x) { $this->gender = $x; } 
	public function setUsername($x) { $this->username = $x; } 
	public function setPassword($x) { $this->password = $x; }
	public function setRegistrationDate($x) { $this->registrationDate = $x; }
	
}