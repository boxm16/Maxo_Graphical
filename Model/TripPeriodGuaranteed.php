<?php

class TripPeriodGuaranteed {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $startTime;
    private $arrivalTime;

    function __construct($type, $startTime, $arrivalTime) {
        $this->type = $type;
        $this->startTime = $startTime;
        $this->arrivalTime = $arrivalTime;
    }

    function getType() {
        return $this->type;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getArrivalTime() {
        return $this->arrivalTime;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    function setArrivalTime($arrivalTime) {
        $this->arrivalTime = $arrivalTime;
    }

}
