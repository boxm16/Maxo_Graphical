<?php

require_once './Controller/TimeCalculator.php';
require_once './Controller/TrafficLightsController.php';

class TripPeriodXL {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $startTimeScheduled;
    private $startTimeActual;
    private $startTimeDifference;
    private $arrivalTimeScheduled;
    private $arrivalTimeActual;
    private $arrivalTimeDifference;
    private $previousTripPeriodArrivalTimeScheduled;
    private $previousTripPeriodArrivalTimeActual;
    private $timeCalculator;
    private $trifficLightsController;

    function __construct($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference) {
        $this->type = $type;
        $this->startTimeScheduled = $startTimeScheduled;
        $this->startTimeActual = $startTimeActual;
        $this->startTimeDifference = $startTimeDifference;
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
        $this->arrivalTimeActual = $arrivalTimeActual;
        $this->arrivalTimeDifference = $arrivalTimeDifference;

        $this->timeCalculator = new TimeCalculator();
        $this->trifficLightsController = new TrafficLightsController();
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
        if ($this->previousTripPeriodArrivalTimeScheduled != "") {
            return $this->timeCalculator->getTimeStampsDifference($this->startTimeScheduled, $this->previousTripPeriodArrivalTimeScheduled);
        } else {
            return "";
        }
    }

    function getHaltTimeActual() {
        if ($this->previousTripPeriodArrivalTimeActual != "" && $this->startTimeActual != "") {
            return $this->timeCalculator->getTimeStampsDifference($this->startTimeActual, $this->previousTripPeriodArrivalTimeActual);
        } else {
            return "";
        }
    }

    function getPreviousTripPeriodArrivalTimeScheduled() {
        return $this->previousTripPeriodArrivalTimeScheduled;
    }

    function getPreviousTripPeriodArrivalTimeActual() {
        return $this->previousTripPeriodArrivalTimeActual;
    }

    function setPreviousTripPeriodArrivalTimeScheduled($previousTripPeriodArrivalTimeScheduled) {
        $this->previousTripPeriodArrivalTimeScheduled = $previousTripPeriodArrivalTimeScheduled;
    }

    function setPreviousTripPeriodArrivalTimeActual($previousTripPeriodArrivalTimeActual) {
        $this->previousTripPeriodArrivalTimeActual = $previousTripPeriodArrivalTimeActual;
    }

    public function getLostTime() {
        if ($this->startTimeDifference != "") {

            $lostTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->startTimeDifference);
            if ($lostTimeInSeconds <= 0) {//if a driver starts earlier than he should start
                // $lostTime = $timeController->getTimeStampFromSeconds($lostTimeInSeconds);
                return $this->startTimeDifference;
            } else {
//dialdi, if a driver starts later then he shoud start, while he could start (has enough halt time) 
                if ($this->getHaltTimeActual() == "") {
                    return "";
                } else {
                    $startTimeDifferenceInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->startTimeDifference);
                    $actualHaltTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->getHaltTimeActual());

                    if ($startTimeDifferenceInSeconds >= $actualHaltTimeInSeconds) {
                        return $this->timeCalculator->getTimeStampFromSeconds($actualHaltTimeInSeconds);
                    } else {
                        return $this->timeCalculator->getTimeStampFromSeconds($startTimeDifferenceInSeconds);
                    }
                }
            }
        } else {
            return "";
        }
    }

    public function getLightsForLostTime() {
        return $this->trifficLightsController->getLightsForStandartTraffic($this->getLostTime());
    }

}
