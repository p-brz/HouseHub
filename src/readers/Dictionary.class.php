<?php

namespace househub\readers;

/**
 * Responsible for translating indexes to strings
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0.0
 */

class Dictionary{
	
	/**
	 * Holds the assoc array
	 * @var array
	 */
	private $source;
	
	/**
	 * Sets the dictionary assoc array
	 * @param array $x
	 */
	public function setSource($x){
		$this->source = $x;
	}
	
	/**
	 * Translates an index based on the given source 
	 * @param string $index
	 */
	public function translate($index){
		if(isset($this->source[$index])){
			return $this->source[$index];
		}else{
			return $index;
		}
	}
}