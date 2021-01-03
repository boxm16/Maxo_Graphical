<?php

include_once 'ExodusXL.php';

class DayXL {

    private $dateStamp;
    private $exoduses;

    function __construct() {
        $this->exoduses = array();
    }

    function getDateStamp() {
        return $this->dateStamp;
    }

    function getExoduses() {
        return $this->exoduses;
    }

    function setDateStamp($dateStamp) {
        $this->dateStamp = $dateStamp;
    }

    function setExoduses($exoduses) {
        $this->exoduses = $exoduses;
    }

    public function getConcurrentlyHaltedBuses() {
        //return array of those perio
        return array();
    }

}
