<?php

namespace househub\access\strategies;

/**
 * Defines the other Access Strategies method
 * 
 * @abstract
 * @access public
 * @author Alison Bento "Lykaios"
 * @version 1.0.1
 */
use househub\answer\AnswerEntity;

abstract class AbstractAccessStrategy {

    abstract public function requestAccess($parameters);

    protected function initializeAnswer() {
        $answer = new AnswerEntity();
        $answer->setStatus(0);
        $answer->setMessage('@error');

        return $answer;
    }

}

?>