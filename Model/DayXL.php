<?php

include_once 'ExodusXL.php';
require_once './Controller/TimeCalculator.php';

class DayXL {

    private $dateStamp;
    private $exoduses;
    private $timeCalculator;

    function __construct() {
        $this->exoduses = array();
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

    public function getVoucherScheduledTimeTableTripPeriods() {
        //returns array of two array of tripPeriods sorted by vaoucher start time scheduled, onew for A_B, and another for B_A 
        $voucherScheduledTimeTableTripPeriods = array();
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
                }
            }
        }
        ksort($ab_intervals);
        ksort($ba_intervals);
        $ab_intervals = $this->setIntervalsForTripPeriods($ab_intervals);
        $ba_intervals = $this->setIntervalsForTripPeriods($ba_intervals);
        array_push($voucherScheduledTimeTableTripPeriods, $ab_intervals);
        array_push($voucherScheduledTimeTableTripPeriods, $ba_intervals);
        return $voucherScheduledTimeTableTripPeriods;
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
                if ($x == 1) {
                    $this->standartIntervalTime = $scheduledInterval;
                    $tripPeriod->setScheduledIntervalColor("white");
                } else {
                    if (abs($this->timeCalculator->getSecondsFromTimeStamp($this->standartIntervalTime) - $this->timeCalculator->getSecondsFromTimeStamp($scheduledInterval)) < 61) {
                        $tripPeriod->setScheduledIntervalColor("white");
                    } else {
                        $tripPeriod->setScheduledIntervalColor("pink");
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

    public function getGPSTimeTableTripPeriods() {
        //returns array of two array of tripPeriods sorted by GPS start time scheduled, one for A_B, and another for B_A 
        $gpsTimeTableTripPeriods = array();
        $a_bArray = array();
        $b_aArray = array();
        foreach ($this->exoduses as $exodus) {
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripPeriods = $tripVoucher->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $tripPeriodType = $tripPeriod->getType();
                    $startTimeActual = $tripPeriod->getStartTimeActual();
                    if ($startTimeActual != "") {
                        if ($tripPeriodType == "ab") {
                            $a_bArray[$startTimeActual] = $tripPeriod;
                        }
                        if ($tripPeriodType == "ba") {
                            $b_aArray[$startTimeActual] = $tripPeriod;
                        }
                    }
                }
            }
        }
        array_push($gpsTimeTableTripPeriods, $a_bArray);
        array_push($gpsTimeTableTripPeriods, $b_aArray);
        return $gpsTimeTableTripPeriods;
    }

    private function getNthItemOfAssociativeArray($nth, $array) {
        $arrayKeys = array_keys($array);
        $index = $arrayKeys[$nth];
        return $array[$index];
    }

}
