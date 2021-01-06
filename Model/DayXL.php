<?php

require_once 'ExodusXL.php';
require_once './Controller/TimeController.php';

class DayXL {

    private $dateStamp;
    private $exoduses;
    private $standartIntervalTime;

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

    public function getIntervals() {
        $intervals = array(); //return array of 2 arrays-one which have inside AB tripPeriods, and other with BA periods, both sorted by startTimeScheduled
        $ab_intervals = array();
        $ba_intervals = array();
        $timeController = new TimeController();

        $exoduses = $this->getExoduses();
        foreach ($exoduses as $exodus) {
            $exodusNumber = $exodus->getNumber();
            $vouchers = $exodus->getTripVouchers();
            foreach ($vouchers as $voucher) {
                $tripPeriods = $voucher->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $tripPeriodDNA = new tripPeriodDNA();
                    $tripPeriodDNA->setExodusNumber($exodusNumber);
                    $tripPeriod->setTripPeriodDNA($tripPeriodDNA);
                    $tripPeriodType = $tripPeriod->getType();
                    if ($tripPeriodType == "ab") {
                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                        $startTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($startTimeScheduled);
                        $ab_intervals[$startTimeScheduledInSeconds] = $tripPeriod;
                        ksort($ab_intervals);
                        $ab_intervals = $this->setIntervalsForTripPeriods($ab_intervals);
                    }
                    if ($tripPeriodType == "ba") {
                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                        $startTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($startTimeScheduled);
                        $ba_intervals[$startTimeScheduledInSeconds] = $tripPeriod;
                        ksort($ba_intervals);
                        $ba_intervals = $this->setIntervalsForTripPeriods($ba_intervals);
                    }
                }
            }
        }
        array_push($intervals, $ab_intervals);
        array_push($intervals, $ba_intervals);
        return $intervals;
    }

    private function setIntervalsForTripPeriods($tripPeriods) {
        $timeController = new TimeController();
        $x = 0;
        while ($x < count($tripPeriods)) {
            if ($x == 0) {
                $tripPeriod = $this->getNthItemOfAssociativeArray($x, $tripPeriods);
                $tripPeriod->setScheduledIntervalAfterPreviousBus("");
                $tripPeriod->setActualIntervalAfterPreviousBus("");
            } else {
                $tripPeriod = $this->getNthItemOfAssociativeArray($x, $tripPeriods);
                $previousTripPeriod = $this->getNthItemOfAssociativeArray($x - 1, $tripPeriods);

                $tripPeriodStartTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($tripPeriod->getStartTimeScheduled());
                $previousTripPeriodStartTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($previousTripPeriod->getStartTimeScheduled());
                $scheduledIntervalInSeconds = $tripPeriodStartTimeScheduledInSeconds - $previousTripPeriodStartTimeScheduledInSeconds;
                $tripPeriod->setScheduledIntervalAfterPreviousBus($timeController->getTimeStampFromSeconds($scheduledIntervalInSeconds));

                //BLOCK INTERVAL_COLOR this block is to set color for standart and prolonged intervals
                if ($x == 1) {
                    $this->standartIntervalTime = $timeController->getTimeStampFromSeconds($scheduledIntervalInSeconds);
                    $tripPeriod->setScheduledIntervalColor("white");
                } else {
                    if (abs($timeController->getSecondsFromTimeStamp($this->standartIntervalTime) - $scheduledIntervalInSeconds) < 61) {
                        $tripPeriod->setScheduledIntervalColor("white");
                    } else {
                        $tripPeriod->setScheduledIntervalColor("pink");
                    }
                }

                //END OF BLOCK INTERVAL_COLOR
                if ($tripPeriod->getStartTimeActual() !== "" && $previousTripPeriod->getStartTimeActual() != "") {
                    $tripPeriodStartTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($tripPeriod->getStartTimeActual());
                    $previousTripPeriodStartTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($previousTripPeriod->getStartTimeActual());
                    $actualIntervalInSeconds = $tripPeriodStartTimeActualInSeconds - $previousTripPeriodStartTimeActualInSeconds;
                    $tripPeriod->setActualIntervalAfterPreviousBus($timeController->getTimeStampFromSeconds($actualIntervalInSeconds));
                } else {
                    $tripPeriod->setActualIntervalAfterPreviousBus("");
                }
            }
            $x++;
        }
        return $tripPeriods;
    }

    private function getNthItemOfAssociativeArray($nth, $array) {
        $arrayKeys = array_keys($array);
        $index = $arrayKeys[$nth];
        return $array[$index];
    }

}
