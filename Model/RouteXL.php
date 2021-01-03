<?php

require_once 'DayXL.php';

class RouteXL {

    private $number;
    private $days;

    function __construct() {
        $this->days = array();
    }
    
    function getNumber() {
        return $this->number;
    }

    function getDays() {
        return $this->days;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setDays($days) {
        $this->days = $days;
    }



}
