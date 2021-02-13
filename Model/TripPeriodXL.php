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
    private $previousTripPeriodArrivalTimeScheduled; //this is for the same bus, diladi, time whe tha same bus ended previous tripPeriod
    private $previousTripPeriodArrivalTimeActual; //this is for the same bus, diladi, time whe tha same bus ended previous tripPeriod
    private $scheduledInterval; //this is time tha has been scheduled to pass from the time previous bus left for same trip
    private $actualInterval; //this is time that  actually has passed from the time previous bus left for same trip 
    private $gpsBasedActualInterval;
    private $scheduledIntervalColor;
    private $tripPeriodDNA;
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

        $startTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->startTimeScheduled);
        $arrivalTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->arrivalTimeScheduled);
        if ($startTimeScheduledInSeconds > $arrivalTimeScheduledInSeconds) {
            $arrivalTimeScheduledInSeconds = $arrivalTimeScheduledInSeconds + (24 * 60 * 60);
        }
        $scheduledTripPeriodTimeInSeconds = $arrivalTimeScheduledInSeconds - $startTimeScheduledInSeconds;
        return $this->timeCalculator->getTimeStampFromSeconds($scheduledTripPeriodTimeInSeconds);
    }

    public function getTripPeriodActualTime() {
        if ($this->startTimeActual != "" && $this->arrivalTimeActual) {
            $startTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->startTimeActual);
            $arrivalTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->arrivalTimeActual);
            if ($startTimeActualInSeconds > $arrivalTimeActualInSeconds) {
                $arrivalTimeActualInSeconds = $arrivalTimeActualInSeconds + (24 * 60 * 60);
            }
            $actualTripPeriodTimeInSeconds = $arrivalTimeActualInSeconds - $startTimeActualInSeconds;
            return $this->timeCalculator->getTimeStampFromSeconds($actualTripPeriodTimeInSeconds);
        }

        return "";
    }

    function getHaltTimeScheduled() {
        if ($this->previousTripPeriodArrivalTimeScheduled != "") {
            $previousTripPeriodArrivalTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->previousTripPeriodArrivalTimeScheduled);
            $startTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->startTimeScheduled);
            if ($startTimeScheduledInSeconds < $previousTripPeriodArrivalTimeScheduledInSeconds) {
                $startTimeScheduledInSeconds = $startTimeScheduledInSeconds + (24 * 60 * 60);
                $haltTimeScheduledInSeconds = $startTimeScheduledInSeconds - $previousTripPeriodArrivalTimeScheduledInSeconds;
                return $this->timeCalculator->getTimeStampFromSeconds($haltTimeScheduledInSeconds);
            } else {
                return $this->timeCalculator->getTimeStampsDifference($this->startTimeScheduled, $this->previousTripPeriodArrivalTimeScheduled);
            }
        } else {
            return "";
        }
    }

    function getHaltTimeActual() {
        if ($this->previousTripPeriodArrivalTimeActual != "" && $this->startTimeActual != "") {
            $previousTripPeriodArrivalTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->previousTripPeriodArrivalTimeActual);
            $startTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->startTimeActual);
            if ($startTimeActualInSeconds < $previousTripPeriodArrivalTimeActualInSeconds) {
                $startTimeActualInSeconds = $startTimeActualInSeconds + (24 * 60 * 60);
                $haltTimeActualInSeconds = $startTimeActualInSeconds - $previousTripPeriodArrivalTimeActualInSeconds;
                return $this->timeCalculator->getTimeStampFromSeconds($haltTimeActualInSeconds);
            } else {

                return $this->timeCalculator->getTimeStampsDifference($this->startTimeActual, $this->previousTripPeriodArrivalTimeActual);
            }
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

    public function getActualIntervalColor() {
        if ($this->scheduledInterval != "" && $this->actualInterval != "") {
            $standartIntervalInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->scheduledInterval);
            $actualIntervalInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->actualInterval);
            $intervalDifference = abs($standartIntervalInSeconds - $actualIntervalInSeconds);
            $intervalDifferenceTimeStamp = $this->timeCalculator->getTimeStampFromSeconds($intervalDifference);

            return $this->trifficLightsController->getLightsForStandartTraffic($intervalDifferenceTimeStamp);
        } else {
            return "white";
        }
    }

    function getTripPeriodDNA() {
        return $this->tripPeriodDNA;
    }

    function setTripPeriodDNA($tripPeriodDNA) {
        $this->tripPeriodDNA = $tripPeriodDNA;
    }

    function getScheduledInterval() {
        return $this->scheduledInterval;
    }

    function getActualInterval() {
        return $this->actualInterval;
    }

    function setScheduledInterval($scheduledInterval) {
        $this->scheduledInterval = $scheduledInterval;
    }

    function setActualInterval($actualInterval) {
        $this->actualInterval = $actualInterval;
    }

    function getScheduledIntervalColor() {
        return $this->scheduledIntervalColor;
    }

    function getGpsBasedActualInterval() {
        return $this->gpsBasedActualInterval;
    }
    
    
    function setGpsBasedActualInterval($gpsBasedActualInterval) {
        $this->gpsBasedActualInterval = $gpsBasedActualInterval;
    }
    
    
      public function getGpsBasedActualIntervalColor() {
        if ($this->scheduledInterval != "" &&  $this->gpsBasedActualInterval != "") {
            $standartIntervalInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->scheduledInterval);
            $gpsBasedActualIntervalInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($this->gpsBasedActualInterval);
            $intervalDifference = abs($standartIntervalInSeconds - $gpsBasedActualIntervalInSeconds);
            $intervalDifferenceTimeStamp = $this->timeCalculator->getTimeStampFromSeconds($intervalDifference);

            return $this->trifficLightsController->getLightsForStandartTraffic($intervalDifferenceTimeStamp);
        } else {
            return "white";
        }
    }

    function setScheduledIntervalColor($scheduledIntervalColor) {
        $this->scheduledIntervalColor = $scheduledIntervalColor;
    }

    public function getStartTimeDifferenceColor() {
        return $this->trifficLightsController->getLightsForRedWhiteTraffic($this->startTimeDifference);
    }

    public function getArrivalTimeDifferenceColor() {
        return $this->trifficLightsController->getLightsForRedWhiteTraffic($this->arrivalTimeDifference);
    }

}
