<?php

require_once 'ExodusGuaranteed.php';
require_once 'Controller/TimeCalculator.php';

class RouteGuaranteed {

    private $number;
    private $baseNumber;
    private $scheme;
    private $busType;
    private $dateStamp;
    private $aPoint;
    private $bPoint;
    private $exoduses;
    private $ABTimeTable;
    private $BATimeTable;
    private $routeEndTime;
    private $timeCalculator;

    function __construct() {
        $this->dateStamp = null;
        $this->exoduses = array();
        $this->ABTimeTable = array();
        $this->BATimeTable = array();
        $this->timeCalculator = new TimeCalculator();
    }

    function getNumber() {
        return $this->number;
    }

    function getBaseNumber() {
        return $this->baseNumber;
    }

    function getScheme() {
        return $this->scheme;
    }

    function getBusType() {
        return $this->busType;
    }

    function getDateStamp() {
        return $this->dateStamp;
    }

    function getAPoint() {
        return $this->aPoint;
    }

    function getBPoint() {
        return $this->bPoint;
    }

    function getExoduses() {
        return $this->exoduses;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setBaseNumber($baseNumber) {
        $this->baseNumber = $baseNumber;
    }

    function setScheme($scheme) {
        $this->scheme = $scheme;
    }

    function setBusType($busType) {
        $this->busType = $busType;
    }

    function setDateStamp($dateStamp) {
        $this->dateStamp = $dateStamp;
    }

    function setAPoint($aPoint) {
        $this->aPoint = $aPoint;
    }

    function setBPoint($bPoint) {
        $this->bPoint = $bPoint;
    }

    function getABTimeTable() {
        return $this->ABTimeTable;
    }

    function getBATimeTable() {
        return $this->BATimeTable;
    }

    function setABTimeTable($ABTimeTable) {
        $this->ABTimeTable = $ABTimeTable;
    }

    function setBATimeTable($BATimeTable) {
        $this->BATimeTable = $BATimeTable;
    }

    function setExoduses($exoduses) {
        $this->exoduses = $exoduses;
    }

    function getRouteEndTime() {
        return $this->routeEndTime;
    }

    function setRouteEndTime($routeEndTime) {
        $this->routeEndTime = $routeEndTime;
    }

    //---//---//---//---//---//---//---//---//---//---//---//---
    public function getExodusesNumber() {
        return count($this->exoduses);
    }

    public function getRouteStartTime() {
        if (count($this->BATimeTable) > 0) {
            $abStartTime = $this->ABTimeTable[0];
            $baStartTime = $this->BATimeTable[0];
            return $abStartTime <= $baStartTime ? $abStartTime : $baStartTime;
        } else {
            return $this->ABTimeTable[0];
        }
    }

    public function getABGuarantyTripPeriodStartTime() {
        if (count($this->ABTimeTable) > 0) {
            return $this->ABTimeTable[count($this->ABTimeTable) - 1];
        } else {
            return "";
        }
    }

    public function getABSubGuarantyTripPeriodStartTime() {
        if (count($this->ABTimeTable) > 1) {
            return $this->ABTimeTable[count($this->ABTimeTable) - 2];
        } else {
            return "";
        }
    }

    public function getBAGuarantyTripPeriodStartTime() {
        if (count($this->BATimeTable) > 0) {
            return $this->BATimeTable[count($this->BATimeTable) - 1];
        } else {
            return "";
        }
    }

    public function getBASubGuarantyTripPeriodStartTime() {
        if (count($this->BATimeTable) > 1) {
            return $this->BATimeTable[count($this->BATimeTable) - 2];
        } else {
            return "";
        }
    }

    public function getStandartIntervalTime() {
        $timeTable = $this->ABTimeTable;
        $intervalsTable = array();
        if (count($timeTable) > 1) {
            for ($x = 1; $x < count($timeTable); $x++) {
                $currentTripPeriodStartTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($timeTable[$x]);
                $previousTripPeriodStartTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($timeTable[$x - 1]);
                $intervalInSeconds = $currentTripPeriodStartTimeInSeconds - $previousTripPeriodStartTimeInSeconds;
                if (array_key_exists($intervalInSeconds, $intervalsTable)) {
                    $count = $intervalsTable[$intervalInSeconds];
                    $count = $count + 1;
                    $intervalsTable[$intervalInSeconds] = $count;
                } else {
                    $intervalsTable[$intervalInSeconds] = 1;
                }
            }
            $mostOccuredIntervals = array_search(max($intervalsTable), $intervalsTable);

            return $this->timeCalculator->getTimeStampFromSecondsMinutesVersion($mostOccuredIntervals);
        } else {
            return "";
        }
    }

    public function getStandartTripPeriodTime() {
        $abTripPeriodTimes = array();
        $baTripPeriodTimes = array();
        $exoduses = $this->exoduses;
        foreach ($exoduses as $exodus) {
            $tripPeriods = $exodus->getTripPeriods();
            foreach ($tripPeriods as $tripPeriod) {
                $tripPeriodType = $tripPeriod->getType();
                //----------------------ab-----------------
                if ($tripPeriodType == "ab") {
                    $startTime = $tripPeriod->getStartTime();
                    $arrivalTime = $tripPeriod->getArrivalTime();
                    $difference = $this->timeCalculator->getTimeStampsDifference($arrivalTime, $startTime);
                    if (array_key_exists($difference, $abTripPeriodTimes)) {
                        $count = $abTripPeriodTimes[$difference];
                        $count = $count + 1;
                        $abTripPeriodTimes[$difference] = $count;
                    } else {
                        $abTripPeriodTimes[$difference] = 1;
                    }
                }
                //------------ba-------------------
                if ($tripPeriodType == "ba") {
                    $startTime = $tripPeriod->getStartTime();
                    $arrivalTime = $tripPeriod->getArrivalTime();
                    $difference = $this->timeCalculator->getTimeStampsDifference($arrivalTime, $startTime);
                    if (array_key_exists($difference, $baTripPeriodTimes)) {
                        $count = $baTripPeriodTimes[$difference];
                        $count = $count + 1;
                        $baTripPeriodTimes[$difference] = $count;
                    } else {
                        $baTripPeriodTimes[$difference] = 1;
                    }
                }
            }
        }
        $abStandartTripPeriodInSeconds = 0;
        $baStandartTripPeriodInSeconds = 0;
        if (count($abTripPeriodTimes) > 0) {
            $mostOccuredABTripPeriods = array_search(max($abTripPeriodTimes), $abTripPeriodTimes);
            // 5 minutes (of halt time) are added to trip time
            $abStandartTripPeriodInSeconds = (5 * 60) + $this->timeCalculator->getSecondsFromTimeStamp($mostOccuredABTripPeriods);
        }

        if (count($baTripPeriodTimes) > 0) {
            $mostOccuredBATripPeriods = array_search(max($baTripPeriodTimes), $baTripPeriodTimes);
            // 5 minutes (of halt time) are added to trip time
            $baStandartTripPeriodInSeconds = (5 * 60) + $this->timeCalculator->getSecondsFromTimeStamp($mostOccuredBATripPeriods);
        }
        $standartTripPeriodTimeInSeconds = $abStandartTripPeriodInSeconds + $baStandartTripPeriodInSeconds;

        return $this->timeCalculator->getTimeStampSansSecondsFromSeconds($standartTripPeriodTimeInSeconds);
    }

    public function getTotalRaces() {
        if (count($this->ABTimeTable) > 0 && count($this->BATimeTable) > 0) {
            return (count($this->ABTimeTable) + count($this->BATimeTable)) / 2;
        }
        if (count($this->ABTimeTable) > 0 && count($this->BATimeTable) == 0) {
            return count($this->ABTimeTable);
        }
        if (count($this->ABTimeTable) == 0 && count($this->BATimeTable) > 0) {
            return count($this->BATimeTable);
        }
        return 0;
    }

    public function getLastBaseReturnTime() {
        $lastBaseReturnTime = "";
        foreach ($this->exoduses as $exodus) {
            if ($lastBaseReturnTime == "") {
                $lastBaseReturnTime = "00:00";
            }
            $tripPeriods = $exodus->getTripPeriods();
            $baseReturnTripPeriod = $tripPeriods[count($tripPeriods) - 1];
            $baseReturnTripPeriodArrialTime = $baseReturnTripPeriod->getArrivalTime();
            if ($lastBaseReturnTime < $baseReturnTripPeriodArrialTime) {
                $lastBaseReturnTime = $baseReturnTripPeriodArrialTime;
            }
          //  echo "Route Number:" . $this->number;
           // echo "// Exodus Number:" . $exodus->getNumber();
            //echo "//BaseReturnTripPeriod Base Arrival Time:" . $baseReturnTripPeriodArrialTime;
            //echo "<br>";
        }
        return $lastBaseReturnTime;
    }

}
