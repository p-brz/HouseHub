<?php

namespace househub\groups\home;

use househub\groups\GroupVisual;
use househub\groups\GroupStructure;

/**
 * A composite object which references distinct data structures.
 * 
 * @author Alison de Araújo Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0
 */
class HomeGroup {

    /**
     * The structure constructor
     */
    public function __construct() {
        $this->structure = null;
        $this->visual = null;
        $this->objects = array();
    }

    /**
     * The group's structure
     * @var GroupStructure
     */
    private $structure;

    /**
     * The group's visual aspects
     * @var GroupVisual
     */
    private $visual;

    /**
     * The group itens
     * @var array
     */
    private $objects;

    public function getUserId() {
        return (is_null($this->structure)) ? null : $this->structure->getUserId();
    }
    
    /**
     * Structure getter
     * @return GroupStructure | null
     */
    public function getStructure() {
        return $this->structure;
    }

    /**
     * Visual getter
     * @return GroupVisual | null
     */
    public function getVisual() {
        return $this->visual;
    }

    /**
     * 
     * @return array | null
     */
    public function getObjects() {
        return $this->objects;
    }

    /**
     * Structure setter
     * @param GroupStructure $x
     */
    public function setStructure($x) {
        $this->structure = $x;
    }

    /**
     * Visual setter
     * @param GroupVisual $x
     */
    public function setVisual($x) {
        $this->visual = $x;
    }

    /**
     * Objects setter
     * @param array $x
     */
    public function setObjects($x) {
        $this->objects = $x;
    }

}

?>