<?php

include_once 'ExodusXL.php';

class DayXL {

    private $dateStamp;
    private $exoduses;

    function __construct() {
        $this->exoduses = array();
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
        $voucherScheduledTimeTableTripPeriods = array();
        foreach ($this->exoduses as $exodus) {
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripPeriods = $tripVoucher->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                    $voucherScheduledTimeTableTripPeriods[$startTimeScheduled] = $tripPeriod;
                }
            }
        }
        return $voucherScheduledTimeTableTripPeriods;
    }

}
