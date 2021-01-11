<?php

require_once './Controller/TimeCalculator.php';

class TripPeriodXL {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $startTimeScheduled;
    private $startTimeActual;
    private $startTimeDifference;
    private $arrivalTimeScheduled;
    private $arrivalTimeActual;
    private $arrivalTimeDifference;
    private $haltTimeScheduled;
    private $haltTimeActual;
    private $timeCalculator;

    function __construct($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference) {
        $this->type = $type;
        $this->startTimeScheduled = $startTimeScheduled;
        $this->startTimeActual = $startTimeActual;
        $this->startTimeDifference = $startTimeDifference;
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
        $this->arrivalTimeActual = $arrivalTimeActual;
        $this->arrivalTimeDifference = $arrivalTimeDifference;

        $this->timeCalculator = new TimeCalculator();
    }

    function getType() {
        return $this->type;
    }

    function getStartTimeScheduled() {
        return $this->startTimeScheduled;
    }

    function getStartTimeActual() {
        return $this->startTimeActual;
    }

    function getStartTimeDifference() {
        return $this->startTimeDifference;
    }

    function getArrivalTimeScheduled() {
        return $this->arrivalTimeScheduled;
    }

    function getArrivalTimeActual() {
        return $this->arrivalTimeActual;
    }

    function getArrivalTimeDifference() {
        return $this->arrivalTimeDifference;
    }

    public function getColor() {
        switch ($this->type) {
            case "baseLeaving":
                return "grey";
            case "break":
                return "yellow";
            case "ab":
                return "blue";
            case "ba":
                return "green";

            case "baseReturn":
                return "grey";
        }
    }

    function getTypeGe() {
        $type = $this->type;
        switch ($type) {
            case "baseLeaving":
                return "ბაზიდან გასვლა";
            case "baseReturn":
                return "ბაზაში დაბრუნება";
            case "break":
                return "შესვენება";
            case "ab":
                return "A_B";
            case "ba":
                return "B_A";
            case "halt":
                return "დგომა A პუნკტში";
        }
    }

    public function getTripPeriodScheduledTime() {
        return $this->timeCalculator->getTimeStampsDifference($this->arrivalTimeScheduled, $this->startTimeScheduled);
    }

    public function getTripPeriodActualTime() {
        if ($this->startTimeActual != "" && $this->arrivalTimeActual != "") {
            return $this->timeCalculator->getTimeStampsDifference($this->arrivalTimeActual, $this->startTimeActual);
        } else {
            return "";
        }
    }
    
    function getHaltTimeScheduled() {
        return $this->haltTimeScheduled;
    }

    function getHaltTimeActual() {
        return $this->haltTimeActual;
    }

    function setHaltTimeScheduled($haltTimeScheduled) {
        $this->haltTimeScheduled = $haltTimeScheduled;
    }

    function setHaltTimeActual($haltTimeActual) {
        $this->haltTimeActual = $haltTimeActual;
    }



}
