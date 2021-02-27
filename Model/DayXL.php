<?php

include_once 'ExodusXL.php';
require_once './Controller/TimeCalculator.php';

class DayXL {

    private $dateStamp;
    private $exoduses;
    private $breaksTimeTable;
    private $timeCalculator;

    function __construct() {
        $this->exoduses = array();
        $this->breaksTimeTable = array();
        $this->timeCalculator = new TimeCalculator();
    }

    function getDateStamp() {
        return $this->dateStamp;
    }

    function getExoduses() {
        return $this->exoduses;
    }

    function setDateStamp($dateStamp) {
        $this->dateStamp = $dateStamp;
    }

    function setExoduses($exoduses) {
        $this->exoduses = $exoduses;
    }

    public function getIntervals() {

        $a_bArray = array();
        $b_aArray = array();
        foreach ($this->exoduses as $exodus) {
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripPeriods = $tripVoucher->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $tripPeriodType = $tripPeriod->getType();

                    $startTimeScheduled_Seconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriod->getStartTimeScheduled());
                    if ($tripPeriodType == "ab") {
                        $ab_intervals[$startTimeScheduled_Seconds] = $tripPeriod;
                    }
                    if ($tripPeriodType == "ba") {
                        $ba_intervals[$startTimeScheduled_Seconds] = $tripPeriod;
                    }
                    if ($tripPeriodType == "break") {
                        $exodusNumber = $exodus->getNumber();
                        $this->breaksTimeTable[$exodusNumber] = $tripPeriod;
                    }
                }
            }
        }
        ksort($ab_intervals);
        ksort($ba_intervals);
        $ab_intervals = $this->setIntervalsForTripPeriods($ab_intervals);
        $ba_intervals = $this->setIntervalsForTripPeriods($ba_intervals);
        $scheduledIntervals = array($ab_intervals, $ba_intervals);


        $ab_GPS_intervals = $this->getGPSBasedIntervalPeriods($ab_intervals);
        $ba_GPS_intervals = $this->getGPSBasedIntervalPeriods($ba_intervals);

        ksort($ab_GPS_intervals);
        ksort($ba_GPS_intervals);

        $ab_GPS_intervals = $this->setGPSBasedIntervalsForTripPeriods($ab_GPS_intervals);
        $ba_GPS_intervals = $this->setGPSBasedIntervalsForTripPeriods($ba_GPS_intervals);


        $gpsIntervals = array($ab_GPS_intervals, $ba_GPS_intervals);
        $intervals["scheduledIntervals"] = $scheduledIntervals;
        $intervals["gpsIntervals"] = $gpsIntervals;
        return $intervals;
    }

    private function setIntervalsForTripPeriods($tripPeriods) {

        $x = 0;
        while ($x < count($tripPeriods)) {
            if ($x == 0) {
                $tripPeriod = $this->getNthItemOfAssociativeArray($x, $tripPeriods);
                $tripPeriod->setScheduledInterval("");
                $tripPeriod->setActualInterval("");
            } else {
                $tripPeriod = $this->getNthItemOfAssociativeArray($x, $tripPeriods);
                $previousTripPeriod = $this->getNthItemOfAssociativeArray($x - 1, $tripPeriods);

                $scheduledInterval = $this->timeCalculator->getTimeStampsDifference($tripPeriod->getStartTimeScheduled(), $previousTripPeriod->getStartTimeScheduled());

                $tripPeriod->setScheduledInterval($scheduledInterval);

                //BLOCK INTERVAL_COLOR this block is to set color for standart and prolonged intervals
                if ($x == 1) {//here i set standartIntervalTime
                    $this->standartIntervalTime = $scheduledInterval;
                    $tripPeriod->setScheduledIntervalColor("white");
                } else {
                    if (abs($this->timeCalculator->getSecondsFromTimeStamp($this->standartIntervalTime) - $this->timeCalculator->getSecondsFromTimeStamp($scheduledInterval)) < 61) {
                        $tripPeriod->setScheduledIntervalColor("white");
                    } else {
                        if ($this->previousExodusOnBreak($tripPeriod)) {
                            $tripPeriod->setScheduledIntervalColor("pink");
                        } else {
                            $tripPeriod->setScheduledIntervalColor("IndianRed");
                        }
                    }
                }

                //END OF BLOCK INTERVAL_COLOR

                if ($tripPeriod->getStartTimeActual() !== "" && $previousTripPeriod->getStartTimeActual() != "") {

                    $tripPeriod->setActualInterval($this->timeCalculator->getTimeStampsDifference($tripPeriod->getStartTimeActual(), $previousTripPeriod->getStartTimeActual()));
                } else {
                    $tripPeriod->setActualInterval("");
                }
            }
            $x++;
        }
        return $tripPeriods;
    }

    private function getGPSBasedIntervalPeriods($tripPeriods) {

        $resultArray = array();
        foreach ($tripPeriods as $tripPeriod) {
            // $tripPeriod_GPS = clone $tripPeriod;//mybe i dont need to clone here
            $startTimeActual = $tripPeriod->getStartTimeActual();
            if ($startTimeActual != "") {
                $startTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($startTimeActual);
                if (array_key_exists($startTimeActualInSeconds, $resultArray)) {
                    $startTimeActualInSeconds++;
                    $resultArray [$startTimeActualInSeconds] = $tripPeriod;
                } else {
                    $resultArray [$startTimeActualInSeconds] = $tripPeriod;
                }
            }
        }return $resultArray;
    }

    private function setGPSBasedIntervalsForTripPeriods($tripPeriods) {
        $x = 0;
        while ($x < count($tripPeriods)) {
            if ($x == 0) {
                $tripPeriod = $this->getNthItemOfAssociativeArray($x, $tripPeriods);

                $tripPeriod->setActualInterval("");
            } else {
                $tripPeriod = $this->getNthItemOfAssociativeArray($x, $tripPeriods);
                $previousTripPeriod = $this->getNthItemOfAssociativeArray($x - 1, $tripPeriods);

                if ($tripPeriod->getStartTimeActual() !== "" && $previousTripPeriod->getStartTimeActual() != "") {

                    $tripPeriod->setGpsBasedActualInterval($this->timeCalculator->getTimeStampsDifference($tripPeriod->getStartTimeActual(), $previousTripPeriod->getStartTimeActual()));
                } else {
                    $tripPeriod->setGpsBasedActualInterval("");
                }
                //hera i check if buses run in scheduled graphic and dont overrun each other
                //vamocmeb ro ertmanets arascrebdnen, da tavis rigis mixedvit midian

                $startTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriod->getStartTimeScheduled());
                $previousTripStartTimeScheduled = $this->timeCalculator->getSecondsFromTimeStamp($previousTripPeriod->getStartTimeScheduled());
                if (($startTimeScheduledInSeconds - $previousTripStartTimeScheduled) < 0) {
                    $tripPeriod->setGSpot("^");
                    $previousTripPeriod->setGSpot("V");
                }
            }
            $x++;
        }
        return $tripPeriods;
    }

    private function previousExodusOnBreak($tripPeriod) {
        $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
        $previousExodusNumber = $exodusNumber - 1;
        if ($previousExodusNumber == 0) {
            $previousExodusNumber = count($this->exoduses);
        }

        if (array_key_exists($previousExodusNumber, $this->breaksTimeTable)) {
            $breakPeriod = $this->breaksTimeTable[$previousExodusNumber];
            $breakStartTime = $this->timeCalculator->getSecondsFromTimeStamp($breakPeriod->getStartTimeScheduled());
            $breakEndTime = $this->timeCalculator->getSecondsFromTimeStamp($breakPeriod->getArrivalTimeScheduled());

            $tripPeriodStartTime = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriod->getStartTimeScheduled());

            if ($tripPeriodStartTime > $breakStartTime && $tripPeriodStartTime < $breakEndTime) {

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //this function is not used yet
    private function periodsCollideInSchedule($tripPeriodOne, $tripPeriodTwo) {
        $periodOneStartTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodOne->getStartTimeScheduled());
        $periodOneArrivalTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodOne->getArrivalTimeScheduled());

        $periodTwoStartTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodTwo->getStartTimeScheduled());
        $periodTwoArrivalTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodTwo->getArrivalTimeScheduled());

        if (($periodTwoStartTimeInSeconds > $periodOneStartTimeInSeconds && $periodTwoStartTimeInSeconds < $periodOneArrivalTimeInSeconds) ||
                ($periodTwoArrivalTimeInSeconds > $periodOneStartTimeInSeconds && $periodTwoArrivalTimeInSeconds < $periodOneArrivalTimeInSeconds)) {
            return true;
        }
        if ($periodOneStartTimeInSeconds == $periodTwoStartTimeInSeconds && $periodOneArrivalTimeInSeconds == $periodTwoArrivalTimeInSeconds) {
            return true;
        }
        return false;
    }

    private function getNthItemOfAssociativeArray($nth, $array) {
        $arrayKeys = array_keys($array);
        $index = $arrayKeys[$nth];
        return $array[$index];
    }

}
