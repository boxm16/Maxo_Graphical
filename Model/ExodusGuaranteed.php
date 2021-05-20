<?php

require_once 'TripPeriodGuaranteed.php';

class ExodusGuaranteed {

    private $number;
    private $tripPeriods;

    function __construct() {
        $this->tripPeriods = array();
    }

    function getNumber() {
        return $this->number;
    }

    function getTripPeriods() {
        return $this->tripPeriods;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setTripPeriods($tripPeriods) {
        $this->tripPeriods = $tripPeriods;
    }

}
