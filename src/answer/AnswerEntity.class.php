<?php

namespace househub\answer;

use househub\json\JsonObject;

class AnswerEntity{
	
	private $status;
	private $message;
	private $content;
	
        
	public function __construct(){
		$this->status = 0;
		$this->message = 'empty';
		$this->content = new JsonObject("content");
	}
	
	public function getStatus() { return $this->status; } 
	public function getMessage() { return $this->message; } 
	public function getContent() { return $this->content; }
	
	public function setStatus($x) { $this->status = $x; } 
	public function setMessage($x) { $this->message = $x; }
	public function setContent($x) { $this->content = $x; }
	
	public function addContent($x) {
		$this->content->addElement($x);
	} 
	
}

?>