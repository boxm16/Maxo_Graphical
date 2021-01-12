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
                        $a_bArray[$startTimeScheduled_Seconds] = $tripPeriod;
                    }
                    if ($tripPeriodType == "ba") {
                        $b_aArray[$startTimeScheduled_Seconds] = $tripPeriod;
                    }
                }
            }
        }
        ksort($a_aArray);
        ksort($b_aArray);
        array_push($voucherScheduledTimeTableTripPeriods, $a_bArray);
        array_push($voucherScheduledTimeTableTripPeriods, $b_aArray);
        return $voucherScheduledTimeTableTripPeriods;
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

}
