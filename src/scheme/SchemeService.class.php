<?php

namespace househub\scheme;

class SchemeService{
	
	private $name;
	private $path;
	private $returnType;
	private $text;
	private $undoService;
	private $parameters;

	public function getName() { return $this->name; } 
	public function getPath() { return $this->path; } 
	public function getReturnType() { return $this->returnType; } 
	public function getText() { return $this->text; } 
	public function getUndoService() { return $this->undoService; } 
	public function getParameters() { return $this->parameters; } 
	
	public function setName($x)         { $this->name = $x; } 
	public function setPath($x)         { $this->path = $x; } 
	public function setReturnType($x)   { $this->returnType = $x; } 
	public function setText($x)         { $this->text = $x; } 
	public function setUndoService($x)  { $this->undoService = $x; } 
	public function setParameters($x)   { $this->parameters = $x; } 

    public function addParameter($x){ $this->parameters[] = $x;}
    //TODO: remover parametro
}

?>
