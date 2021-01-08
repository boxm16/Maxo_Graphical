<?php

require_once 'ExodusXL.php';
require_once './Controller/TimeController.php';
require_once './Controller/ColorController.php';

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

        $ab_GPS_intervals = array();
        $ba_GPS_intervals = array();
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

                        //-----------
                        $tripPeriod_GPS = clone $tripPeriod;
                        $startTimeActual = $tripPeriod_GPS->getStartTimeActual();
                        if ($startTimeActual != "") {
                            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($startTimeActual);
                            if (array_key_exists($startTimeActualInSeconds, $ab_GPS_intervals)) {
                                $startTimeActualInSeconds++;
                                $ab_GPS_intervals [$startTimeActualInSeconds] = $tripPeriod_GPS;
                            } else {
                                $ab_GPS_intervals [$startTimeActualInSeconds] = $tripPeriod_GPS;
                            }
                        }

                        //---end----
                    }
                    if ($tripPeriodType == "ba") {
                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                        $startTimeScheduledInSeconds = $timeController->getSecondsFromTimeStamp($startTimeScheduled);
                        $ba_intervals[$startTimeScheduledInSeconds] = $tripPeriod;


                        //-----------
                        $tripPeriod_GPS = clone $tripPeriod;
                        $startTimeActual = $tripPeriod_GPS->getStartTimeActual();
                        if ($startTimeActual != "") {
                            $startTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($startTimeActual);
                            if (array_key_exists($startTimeActualInSeconds, $ba_GPS_intervals)) {
                                $startTimeActualInSeconds++;
                                $ba_GPS_intervals [$startTimeActualInSeconds] = $tripPeriod_GPS;
                            } else {
                                $ba_GPS_intervals [$startTimeActualInSeconds] = $tripPeriod_GPS;
                            }
                        }


                        //---end----
                    }
                }
            }
        }
        ksort($ab_intervals);
        $ab_intervals = $this->setIntervalsForTripPeriods($ab_intervals);
        ksort($ba_intervals);
        $ba_intervals = $this->setIntervalsForTripPeriods($ba_intervals);
        ksort($ab_GPS_intervals);
        $ab_GPS_intervals = $this->setIntervalsForTripPeriods($ab_GPS_intervals);
        ksort($ba_GPS_intervals);
        $ba_GPS_intervals = $this->setIntervalsForTripPeriods($ba_GPS_intervals);

        $scheduledIntervals = array($ab_intervals, $ba_intervals);
        $gpsIntervals = array($ab_GPS_intervals, $ba_GPS_intervals);
        $intervals["scheduledIntervals"] = $scheduledIntervals;
        $intervals["gpsIntervals"] = $gpsIntervals;
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
                    if ($x >= 1) {
                        $scheduledIntervalTimeInSeconds = $timeController->getSecondsFromTimeStamp($tripPeriod->getScheduledIntervalAfterPreviousBus());
                        $actualIntervalDifference = abs($scheduledIntervalTimeInSeconds - $actualIntervalInSeconds);
                        $colorController = new ColorController();
                        $tripPeriod->setActualIntervalColor($colorController->getIntervalColor($actualIntervalDifference));
                    } else {
                        $tripPeriod->setActualIntervalColor("white");
                    }
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
