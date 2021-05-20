<?php

class TripPeriodGuaranteed {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $startTimeScheduled;
    private $arrivalTimeScheduled;

    function __construct($type, $startTimeScheduled, $arrivalTimeScheduled) {
        $this->type = $type;
        $this->startTimeScheduled = $startTimeScheduled;
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
    }

    function getType() {
        return $this->type;
    }

    function getStartTimeScheduled() {
        return $this->startTimeScheduled;
    }

    function getArrivalTimeScheduled() {
        return $this->arrivalTimeScheduled;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setStartTimeScheduled($startTimeScheduled) {
        $this->startTimeScheduled = $startTimeScheduled;
    }

    function setArrivalTimeScheduled($arrivalTimeScheduled) {
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
    }

}
