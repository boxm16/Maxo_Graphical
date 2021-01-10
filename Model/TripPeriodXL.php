<?php

class TripPeriodXL {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $startTimeScheduled;
    private $startTimeActual;
    private $startTimeDifference;
    private $arrivalTimeScheduled;
    private $arrivalTimeActual;
    private $arrivalTimeDifference;

    function __construct($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference) {
        $this->type = $type;
        $this->startTimeScheduled = $startTimeScheduled;
        $this->startTimeActual = $startTimeActual;
        $this->startTimeDifference = $startTimeDifference;
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
        $this->arrivalTimeActual = $arrivalTimeActual;
        $this->arrivalTimeDifference = $arrivalTimeDifference;
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

}
