<?php

require_once './Controller/TimeController.php';

class TripPeriodXL {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $startTimeScheduled;
    private $startTimeActual;
    private $startTimeDifference;
    private $arrivalTimeScheduled;
    private $arrivalTimeActual;
    private $arrivalTimeDifference;
    private $availableDepartureTimeAtLateDeparture;
    private $actualTripPeriodTime;
    private $previosTripPeriodArrivalTimeActual;
    private $actualHaltTime;

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
        }
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

    function getAvailableDepartureTimeAtLateDeparture() {
        return $this->availableDepartureTimeAtLateDeparture;
    }

    function setAvailableDepartureTimeAtLateDeparture($availableDepartureTimeAtLateDeparture) {
        $this->availableDepartureTimeAtLateDeparture = $availableDepartureTimeAtLateDeparture;
    }

    function getActualHaltTime() {
        if ($this->startTimeActual != "" && $this->previosTripPeriodArrivalTimeActual != "") {
            $timeController = new TimeController();
            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeActual);
            $previosTripPeriodArrivalTimeActual = $timeController->getSecondsFromTimeStamp($this->previosTripPeriodArrivalTimeActual);
            $actualHaltTimeInSeconds = $startTimeActualInSeconds - $previosTripPeriodArrivalTimeActual;
            return $timeController->getTimeStampFromSeconds($actualHaltTimeInSeconds);
        }
        return "";
    }

    function setActualHaltTime($actualHaltTime) {
        $this->actualHaltTime = $actualHaltTime;
    }

    function getActualTripPeriodTime() {
        if ($this->startTimeActual != "" && $this->arrivalTimeActual) {
            $timeController = new TimeController();
            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeActual);
            $arrivalTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->arrivalTimeActual);
            $actualTripPeriodTimeInSeconds = $arrivalTimeActualInSeconds - $startTimeActualInSeconds;
            return $timeController->getTimeStampFromSeconds($actualTripPeriodTimeInSeconds);
        }

        return "";
    }

    function setActualTripPeriodTime($actualTripPeriodTime) {
        $this->actualTripPeriodTime = $actualTripPeriodTime;
    }

    function getPreviosTripPeriodArrivalTimeActual() {
        return $this->previosTripPeriodArrivalTimeActual;
    }

    function setPreviosTripPeriodArrivalTimeActual($previosTripPeriodArrivalTimeActual) {
        $this->previosTripPeriodArrivalTimeActual = $previosTripPeriodArrivalTimeActual;
    }

    public function getLightsForHaltTimeAtLateDeparture() {
        if ($this->startTimeActual != "" && $this->startTimeScheduled) {
            $timeController = new TimeController();
            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeActual);
            $startTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeScheduled);
            if ($startTimeActualInSeconds - $startTimeScheduledInSeconds > 0) {
                $actualHaltTime = $this->getActualHaltTime();
                if ($actualHaltTime == "") {
                    return "white";
                } else {
                    $actualHaltTimeInSeconds = $timeController->getSecondsFromTimeStamp($actualHaltTime);
                    if ($actualHaltTimeInSeconds < (5 * 60)) {
                        return "yellow";
                    } else {
                        return "red";
                    }
                }
            } else
                return "green";
        } else
            return "white";
    }

}
