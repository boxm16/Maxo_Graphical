<?php

require_once './Controller/TimeController.php';
require_once 'DNA.php';

class TripPeriodXL {

    private $type; //baseLeaving, baseReturn, ab, ba, break
    private $departurePoint; //a,b,d (d is a base)
    private $startTimeScheduled;
    private $startTimeActual;
    private $startTimeDifference;
    private $arrivalTimeScheduled;
    private $arrivalTimeActual;
    private $arrivalTimeDifference;
    private $availableDepartureTimeAtLateDeparture;
    private $scheduledIntervalAfterPreviousBus; //this is time tha has been scheduled to pass from the time previous bus left for same trip
    private $actualIntervalAfterPreviousBus; //this is time that  actually has passed from the time previous bus left for same trip 
    private $previosTripPeriodArrivalTimeScheduled; //this is for the same bus, diladi, time whe tha same bus ended previous tripPeriod
    private $previosTripPeriodArrivalTimeActual;
    private $tripPeriodDNA;

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
            case "halt":
                return "დგომა A პუნკტში";
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
            case "halt":
                return "LightGray";
        }
    }

    function getAvailableDepartureTimeAtLateDeparture() {
        return $this->availableDepartureTimeAtLateDeparture;
    }

    function setAvailableDepartureTimeAtLateDeparture($availableDepartureTimeAtLateDeparture) {
        $this->availableDepartureTimeAtLateDeparture = $availableDepartureTimeAtLateDeparture;
    }

    public function getScheduledHaltTime() {
        if ($this->previosTripPeriodArrivalTimeScheduled != "") {
            $timeController = new TimeController();
            $startTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeScheduled);
            $previosTripPeriodArrivalTimeScheduled = $timeController->getSecondsFromTimeStamp($this->previosTripPeriodArrivalTimeScheduled);
            $ScheduledHaltTimeInSeconds = $startTimeScheduledInSeconds - $previosTripPeriodArrivalTimeScheduled;
            return $timeController->getTimeStampFromSeconds($ScheduledHaltTimeInSeconds);
        }
        return "";
    }

    public function getActualHaltTime() {
        if ($this->startTimeActual != "" && $this->previosTripPeriodArrivalTimeActual != "") {
            $timeController = new TimeController();
            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeActual);
            $previosTripPeriodArrivalTimeActual = $timeController->getSecondsFromTimeStamp($this->previosTripPeriodArrivalTimeActual);
            $actualHaltTimeInSeconds = $startTimeActualInSeconds - $previosTripPeriodArrivalTimeActual;
            return $timeController->getTimeStampFromSeconds($actualHaltTimeInSeconds);
        }
        return "";
    }

    public function getScheduledTripPeriodTime() {
        $timeController = new TimeController();
        $startTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeScheduled);
        $arrivalTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($this->arrivalTimeScheduled);
        if ($startTimeScheduledInSeconds > $arrivalTimeScheduledInSeconds) {
            $arrivalTimeScheduledInSeconds = $arrivalTimeScheduledInSeconds + (24 * 60 * 60);
        }
        $scheduledTripPeriodTimeInSeconds = $arrivalTimeScheduledInSeconds - $startTimeScheduledInSeconds;
        return $timeController->getTimeStampFromSeconds($scheduledTripPeriodTimeInSeconds);
    }

    public function getActualTripPeriodTime() {
        if ($this->startTimeActual != "" && $this->arrivalTimeActual) {
            $timeController = new TimeController();
            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeActual);
            $arrivalTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($this->arrivalTimeActual);
            if ($startTimeActualInSeconds > $arrivalTimeActualInSeconds) {
                $arrivalTimeActualInSeconds = $arrivalTimeActualInSeconds + (24 * 60 * 60);
            }
            $actualTripPeriodTimeInSeconds = $arrivalTimeActualInSeconds - $startTimeActualInSeconds;
            return $timeController->getTimeStampFromSeconds($actualTripPeriodTimeInSeconds);
        }

        return "";
    }

    function getPreviosTripPeriodArrivalTimeScheduled() {
        return $this->previosTripPeriodArrivalTimeScheduled;
    }

    function setPreviosTripPeriodArrivalTimeScheduled($previosTripPeriodArrivalTimeScheduled) {
        $this->previosTripPeriodArrivalTimeScheduled = $previosTripPeriodArrivalTimeScheduled;
    }

    function getPreviosTripPeriodArrivalTimeActual() {
        return $this->previosTripPeriodArrivalTimeActual;
    }

    function setPreviosTripPeriodArrivalTimeActual($previosTripPeriodArrivalTimeActual) {
        $this->previosTripPeriodArrivalTimeActual = $previosTripPeriodArrivalTimeActual;
    }

    function getTripPeriodDNA() {
        return $this->tripPeriodDNA;
    }

    function setTripPeriodDNA($tripPeriodDNA) {
        $this->tripPeriodDNA = $tripPeriodDNA;
    }

    function getDeparturePoint() {
        return $this->departurePoint;
    }

    function setDeparturePoint($departurePoint) {
        $this->departurePoint = $departurePoint;
    }

    function getScheduledIntervalAfterPreviousBus() {
        return $this->scheduledIntervalAfterPreviousBus;
    }

    function setScheduledIntervalAfterPreviousBus($scheduledIntervalAfterPreviousBus) {
        $this->scheduledIntervalAfterPreviousBus = $scheduledIntervalAfterPreviousBus;
    }

        function getActualIntervalAfterPreviousBus() {
        return $this->actualIntervalAfterPreviousBus;
    }

    function setActualIntervalAfterPreviousBus($actualIntervalAfterPreviousBus) {
        $this->actualIntervalAfterPreviousBus = $actualIntervalAfterPreviousBus;
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

    public function getLostTime() {
        if ($this->startTimeDifference != "") {

            $timeController = new TimeController();
            $lostTimeInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeDifference);
            if ($lostTimeInSeconds <= 0) {//if a driver starts earlier than he should start
                $lostTime = $timeController->getTimeStampFromSeconds($lostTimeInSeconds);

                return $lostTime;
            } else {
//dialdi, if a driver starts later then he shoud start, while he could start (has enough halt time) 
                $actualHaltTime = $this->getActualHaltTime();
                if ($actualHaltTime == "") {
                    return "";
                } else {
                    $startTimeDifferenceInSeconds = $timeController->getSecondsFromTimeStamp($this->startTimeDifference);
                    $actualHaltTimeInSeconds = $timeController->getSecondsFromTimeStamp($actualHaltTime);

                    if ($startTimeDifferenceInSeconds >= $actualHaltTimeInSeconds) {
                        return $timeController->getTimeStampFromSeconds($actualHaltTimeInSeconds);
                    } else {
                        return $timeController->getTimeStampFromSeconds($startTimeDifferenceInSeconds);
                    }
                }
            }
        } else {
            return "";
        }
    }

    public function getLightsForTimeStamp($timeStamp) {
        if ($timeStamp == "") {
            return "white";
        }
        $timeController = new TimeController();
        $timeInSeconds = $timeController->getSecondsFromTimeStamp($timeStamp);
        if ($timeInSeconds < 61 && $timeInSeconds > -60) {
            return "white";
        }
        if ($timeInSeconds < 301 && $timeInSeconds > -300) {
            return "yellow";
        }
        return "red";
    }

}
