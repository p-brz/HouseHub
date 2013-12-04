<?php

namespace househub\objects;

class ObjectStructure {

    private $id;
    private $type;
    private $address;
    private $schemeName;
    private $parentId;
    private $parentIndex;
    private $validated;
    private $connected;
    private $registrationDate;

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getSchemeName() {
        return $this->schemeName;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function getParentIndex() {
        return $this->parentIndex;
    }

    public function getValidated() {
        return $this->validated;
    }

    public function getConnected() {
        return $this->connected;
    }

    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    public function setId($x) {
        $this->id = $x;
    }

    public function setType($x) {
        $this->type = $x;
    }

    public function setAddress($x) {
        $this->address = $x;
    }

    public function setSchemeName($x) {
        $this->schemeName = $x;
    }

    public function setParentId($x) {
        $this->parentId = $x;
    }

    public function setParentIndex($x) {
        $this->parentIndex = $x;
    }

    public function setValidated($x) {
        $this->validated = $x;
    }

    public function setConnected($x) {
        $this->connected = $x;
    }

    public function setRegistrationDate($x) {
        $this->registrationDate = $x;
    }

}
