<?php

class Item {

    private $id;
    private $barcode;
    private $description;
    private $notes;
    //---------
    private $quantity;
    function getId() {
        return $this->id;
    }

    function getBarcode() {
        return $this->barcode;
    }

    function getDescription() {
        return $this->description;
    }

    function getNotes() {
        return $this->notes;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setBarcode($barcode) {
        $this->barcode = $barcode;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setNotes($notes) {
        $this->notes = $notes;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }



}
