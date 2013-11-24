<?php

namespace Core\Access\Strategies\System;

/**
 * Called when something goes wrong
 * 
 * Caso algo errado aconteça, esta estratégia deve ser assumida.
 * 
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.0.0
 */
use Core\Access\Strategies\AbstractAccessStrategy;

use Core\Answer\JsonAnswerParser;

use Core\Answer\AnswerEntity;

class CougarAccessStrategy extends AbstractAccessStrategy{
	
	public function requestAccess($parameters){
		$answer = new AnswerEntity();
		$answer->setStatus(0);
		$answer->setMessage('@empty');
		
		
		return $answer;
		
	}
	
}

?>