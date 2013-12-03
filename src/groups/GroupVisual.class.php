<?php

namespace househub\groups;

/**
 * The structure of a group visual aspects
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0
 */
class GroupVisual{
	
	/**
	 * The visual id
	 * @var integer
	 */
	private $id;
	
	/**
	 * The owner id
	 * @var integer
	 */
	private $userId;
	
	/**
	 * The group id which these rules applies
	 * @var id
	 */
	private $groupId;
	
	/**
	 * The group name
	 * @var string
	 */
	private $groupName;
	
	/**
	 * The image's id
	 * @var integer
	 */
	private $groupImageId;
	
	/**
	 * The visual's creation date
	 * @var string
	 */
	private $setDate;
	
	public function getId() { return $this->id; } 
	public function getUserId() { return $this->userId; } 
	public function getGroupId() { return $this->groupId; } 
	public function getGroupName() { return $this->groupName; } 
	public function getGroupImageId() { return $this->groupImageId; } 
	public function getSetDate() { return $this->setDate; } 
	
	public function setId($x) { $this->id = $x; } 
	public function setUserId($x) { $this->userId = $x; } 
	public function setGroupId($x) { $this->groupId = $x; } 
	public function setGroupName($x) { $this->groupName = $x; } 
	public function setGroupImageId($x) { $this->groupImageId = $x; } 
	public function setSetDate($x) { $this->setDate = $x; } 
}