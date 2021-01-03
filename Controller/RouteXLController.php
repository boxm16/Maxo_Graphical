<?php

require_once "SimpleXLSX.php";
require_once 'Model/RouteXL.php';
require_once 'TimeController.php';

class RouteXLController {

    private $routes; //this is array of routes

    function __construct() {

        $excelRows = $this->readExcelFile();
        $this->routes = $this->getRoutesFromExcelRows($excelRows); //array for routes
    }

    private function readExcelFile() {
        if ($xlsx = SimpleXLSX::parse('uploads/excellFile.xlsx')) {
            $rows = $xlsx->rowsEx();
        } else {
            echo "ფაილი არ არის ატვირთული ან დაზიანებულია(" . SimpleXLSX::parseError() . ")";
            echo "<hr>";
            return;
        }
        return $rows;
    }

    private function getRoutesFromExcelRows($excelRows) {
        $routes = array();
        foreach ($excelRows as $row) {
            // sifting off empty rows and rows that show something else
            if ($row[7]["value"] == "მარშრუტი" || $row[7]["value"] == "კონდუქტორი" || $row[7]["value"] == "") {

                continue;
            }
            $routeNumber = $row[7]["value"];
            if (array_key_exists($routeNumber, $routes)) {
                $existingRoute = $routes[$routeNumber];
                $refilledRoute = $this->addRowElementsToRoute($existingRoute, $row);
                $routes[$routeNumber] = $refilledRoute;
            } else {
                $newRoute = new RouteXL();
                $newRoute->setNumber($routeNumber);
                $refilledRoute = $this->addRowElementsToRoute($newRoute, $row);
                $routes[$routeNumber] = $refilledRoute;
            }
        }
        return $routes;
    }

    private function addRowElementsToRoute($route, $row) {
        $dateStamp = $row[5]["value"];
        $days = $route->getDays();
        if (array_key_exists($dateStamp, $days)) {
            $existingDay = $days[$dateStamp];
            $refilledDay = $this->addElementsToExistingDay($existingDay, $row);
            $days[$dateStamp] = $refilledDay;
        } else {
            $newDay = new DayXL();
            $newDay->setDateStamp($dateStamp);
            $refilledDay = $this->addElementsToExistingDay($newDay, $row);
            $days[$dateStamp] = $refilledDay;
        }
        $route->setDays($days);
        return $route;
    }

    private function addElementsToExistingDay($day, $row) {
        $exodusNumber = $row[8]["value"];
        $exoduses = $day->getExoduses();
        if (array_key_exists($exodusNumber, $exoduses)) {
            $existingExodus = $exoduses[$exodusNumber];
            $refilledExodus = $this->addElementsToExistingExodus($existingExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        } else {
            $newExodus = new ExodusXL();
            $newExodus->setNumber($exodusNumber);
            $refilledExodus = $this->addElementsToExistingExodus($newExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        }
        $day->setExoduses($exoduses);
        return $day;
    }

    private function addElementsToExistingExodus($exodus, $row) {
        $tripVoucherNumber = $row[6]["value"];
        $vouchers = $exodus->getTripVouchers();
        if (array_key_exists($tripVoucherNumber, $vouchers)) {
            $existingVoucher = $vouchers[$tripVoucherNumber];
            $refilledVoucher = $this->addElementsToExistingTripVoucher($existingVoucher, $row);
            $vouchers[$tripVoucherNumber] = $refilledVoucher;
        } else {
            $busNumber = $row[2]["value"];
            $busType = $row[1]["value"];
            $driverNumber = $row[3]["value"];
            $driverName = $row[4]["value"];
            $notes = $row[26]["value"];

            $newTripVoucher = new TripVoucherXL();
            $newTripVoucher->setNumber($tripVoucherNumber);
            $newTripVoucher->setBusNumber($busNumber);
            $newTripVoucher->setBusType($busType);
            $newTripVoucher->setDriverNumber($driverNumber);
            $newTripVoucher->setDriverName($driverName);
            $newTripVoucher->setNotes($notes);
            $refilledVoucher = $this->addElementsToExistingTripVoucher($newTripVoucher, $row);
            $vouchers[$tripVoucherNumber] = $refilledVoucher;
        }
        $exodus->setTripVouchers($vouchers);
        return $exodus;
    }

    private function addElementsToExistingTripVoucher($tripVoucher, $row) {
        $tripPeriods = $tripVoucher->getTripPeriods();
        $tripPeriodTypeStampInRowCell = $row[13]["value"];
        $tripPeriodType = $this->getTripTypeFromTripStampInRowCell($tripPeriodTypeStampInRowCell);
        if ($tripPeriodType == "baseLeaving") {
            $tripPeriod = $this->createBaseLeavingPeriod($row);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "baseReturn") {
            $tripPeriod = $this->createBaseReturnPeriod($row);
            $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "break") {
            $tripPeriod = $this->createBreakPeriod($row);
            $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "round") {
            $tripPeriodsOfRound = $this->createTripPeridsOfRound($row);
            foreach ($tripPeriodsOfRound as $tripPeriod) {
                $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
                array_push($tripPeriods, $tripPeriod);
            }
        }

        $tripVoucher->setTripPeriods($tripPeriods);
        return $tripVoucher;
    }

    private function createBaseLeavingPeriod($row) {
        $tripPeriodType = "baseLeaving";
        if ($row[14]["value"] != "") {
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBaseReturnPeriod($row) {
        $tripPeriodType = "baseReturn";
        if ($row[14]["value"] != "") {
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBreakPeriod($row) {
        $tripPeriodType = "break";
        if ($row[14]["value"] != "") {
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createTripPeridsOfRound($row) {
        $tripPeriodsOfRound = array();
        if ($row[14]["value"] != "" && $row[20]["value"] != "") {
            $leftSideTime = $row[14]["value"];
            $rightSideTime = $row[20]["value"];
            $timeController = new TimeController();


            $leftSideTimeInSeconds = $timeController->getSecondsFromTimeStamp($leftSideTime);
            $rightSideTimeInSeconds = $timeController->getSecondsFromTimeStamp($rightSideTime);

            if ($leftSideTimeInSeconds < $rightSideTimeInSeconds) {
                $tripPeriodType = "ab";
                $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                $tripPeriodType = "ba";
                $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                return $tripPeriodsOfRound;
            } else {

                $tripPeriodType = "ba";
                $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                $tripPeriodType = "ab";
                $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                return $tripPeriodsOfRound;
            }
        }
        if ($row[14]["value"] != "") {
            $tripPeriodType = "ab";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        if ($row[20]["value"] != "") {
            $tripPeriodType = "ba";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        return $tripPeriodsOfRound;
    }

    private function getTripTypeFromTripStampInRowCell($tripPeriodTypeStampInRowCell) {
        //baseLeaving, baseReturn, ab, ba, break

        if (strpos($tripPeriodTypeStampInRowCell, "გასვლა") != null) {
            return "baseLeaving";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "შესვენება") != null) {
            return "break";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "დაბრუნება") != null) {
            return "baseReturn";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "ბრუნი") != null) {
            return "round";
        }
    }

    private function createTripPeriodFromLeftSide($row, $type) {

        $startTimeScheduled = $row[14]["value"];
        $startTimeActual = $row[15]["value"];
        $startTimeDifference = $row[16]["value"];
        $arrivalTimeScheduled = $row[17]["value"];
        $arrivalTimeActual = $row[18]["value"];
        $arrivalTimeDifference = $row[19]["value"];
        $tripPeriod = new TripPeriodXL($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
        $tripPeriod->setDeparturePoint("a");
        return $tripPeriod;
    }

    private function createTripPeriodFromRightSide($row, $type) {
        $startTimeScheduled = $row[20]["value"];
        $startTimeActual = $row[21]["value"];
        $startTimeDifference = $row[22]["value"];
        $arrivalTimeScheduled = $row[23]["value"];
        $arrivalTimeActual = $row[24]["value"];
        $arrivalTimeDifference = $row[25]["value"];
        $tripPeriod = new TripPeriodXL($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
        $tripPeriod->setDeparturePoint("b");
        return $tripPeriod;
    }

    private function addPreviosTripPeriodTimes($tripPeriod, $tripPeriods) {

        $previousTripPeriod = $tripPeriods[count($tripPeriods) - 1];
        $previousTripPeriodArrivalTimeActual = $previousTripPeriod->getArrivalTimeActual();
        $previousTripPeriodArrivalTimeScheduled = $previousTripPeriod->getArrivalTimeScheduled();
        $tripPeriod->setPreviosTripPeriodArrivalTimeActual($previousTripPeriodArrivalTimeActual);
        $tripPeriod->setPreviosTripPeriodArrivalTimeScheduled($previousTripPeriodArrivalTimeScheduled);
        return $tripPeriod;
    }

    //--------------------------------------------------
    //----------------------------------------------
    //-------------------------

    public function getRoutes() {
        return $this->routes;
    }

    public function setRoutes($routes) {
        $this->routes = $routes;
    }

    public function routeCasesOfConcurrentlyHaltedBuses() {
        //return array(cases) of arrays of concurrently halted buses

        $routeArrayOfCases = array();
        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();
            $days = $route->getDays();

            foreach ($days as $day) {
                $sortedTripPeriodsOfTheDay = $this->getSortedTripPeriodsOfTheDay($day, $routeNumber);
                $arrayOfDayCasesOfConcurrentlyHaltedBuses = $this->getArrayOfDayCasesOfConcurrentlyHaltedBuses($sortedTripPeriodsOfTheDay);
                array_push($routeArrayOfCases, $arrayOfDayCasesOfConcurrentlyHaltedBuses);
            }
        }
        return $routeArrayOfCases;
    }

    private function getSortedTripPeriodsOfTheDay($day, $routeNumber) {
        $timeController = new TimeController();
        $allTripPeriodsOfTheDay = array();
        $dateStamp = $day->getDateStamp();
        $exoduses = $day->getExoduses();
        foreach ($exoduses as $exodus) {
            $exodusNumber = $exodus->getNumber();
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripVoucherNumber = $tripVoucher->getNumber();
                // $busNumber=$tripVoucher->getBusNumber();
                //$busType=$tripVoucher->getBusType();
                $driverNumber = $tripVoucher->getDriverNumber();
                $driverName = $tripVoucher->getDriverName();
                $notes = $tripVoucher->getNotes();
                $tripPeriods = $tripVoucher->getTripPeriods();
                for ($index = 0; $index < count($tripPeriods); $index++) {
                    $tripPeriod = $tripPeriods[$index];
                    $tripPeriodType = $tripPeriod->getType();
                    if ($tripPeriodType != "baseLeaving") {
                        $tripPeriodDNA = new tripPeriodDNA();
                        $tripPeriodDNA->setRouteNumber($routeNumber);
                        $tripPeriodDNA->setDateStamp($dateStamp);
                        $tripPeriodDNA->setExodusNumber($exodusNumber);
                        $tripPeriodDNA->setVoucherNumber($tripVoucherNumber);
                        $tripPeriodDNA->setDriverNumber($driverNumber);
                        $tripPeriodDNA->setDriverName($driverName);
                        $tripPeriodDNA->setNotes($notes);
                        $tripPeriod->setTripPeriodDNA($tripPeriodDNA);
                        $haltStartTimeActual = $tripPeriod->getPreviosTripPeriodArrivalTimeActual();
                        $startTimeActual = $tripPeriod->getStartTimeActual();
                        if ($haltStartTimeActual != "" && $startTimeActual != "") {
                            $haltStartTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($haltStartTimeActual);
                            if (array_key_exists($haltStartTimeActualInSeconds, $allTripPeriodsOfTheDay)) {
                                $allTripPeriodsOfTheDay[$haltStartTimeActualInSeconds + 1] = $tripPeriod;
                            } else {
                                $allTripPeriodsOfTheDay[$haltStartTimeActualInSeconds] = $tripPeriod;
                            }
                        }
                    }
                }
            }
        }
        ksort($allTripPeriodsOfTheDay);
        return $allTripPeriodsOfTheDay;
    }

    private function getArrayOfDayCasesOfConcurrentlyHaltedBuses($sortedTripPeriodsOfTheDay) {
        $arrayOfDayCasesOfConcurrentlyHaltedBuses = array();
        $arrayKeys = array_keys($sortedTripPeriodsOfTheDay);
        for ($x = 0; $x < count($arrayKeys); $x++) {
            $tripPeriod = $this->getNthItemOfAssociativeArray($x, $sortedTripPeriodsOfTheDay);

            $caseOfConcurrentlyHaltedBuses = $this->getCaseOfConcurrentlyHaltedBuses($x, $tripPeriod, $sortedTripPeriodsOfTheDay);
            if (count($caseOfConcurrentlyHaltedBuses) > 1) {
                array_push($arrayOfDayCasesOfConcurrentlyHaltedBuses, $caseOfConcurrentlyHaltedBuses);
            }
        }

        return $arrayOfDayCasesOfConcurrentlyHaltedBuses;
    }

    private function getNthItemOfAssociativeArray($nth, $array) {
        $arrayKeys = array_keys($array);
        $index = $arrayKeys[$nth];
        return $array[$index];
    }

    private function getCaseOfConcurrentlyHaltedBuses($x, $tripPeriod, $sortedTripPeriodsOfTheDay) {
        $caseOfConccurentlyHaltedBuses = array();
        $loopEndPoint = $tripPeriod->getStartTimeActual();
        $timeController = new TimeController();
        $loopEndPointInSeconds = $timeController->getSecondsFromTimeStamp($loopEndPoint);
        $x = $x + 1;
        array_push($caseOfConccurentlyHaltedBuses, $tripPeriod);
        while ($x < count($sortedTripPeriodsOfTheDay)) {
            $comparingTripPeriod = $this->getNthItemOfAssociativeArray($x, $sortedTripPeriodsOfTheDay);
            $comparingTripPeriodHaltStartTimeActual = $comparingTripPeriod->getPreviosTripPeriodArrivalTimeActual();
            $comparingTripPeriodHaltStartTimeActualInSeconds = $timeController->getSecondsFromTimeStamp($comparingTripPeriodHaltStartTimeActual);
            if ($comparingTripPeriodHaltStartTimeActualInSeconds >= $loopEndPointInSeconds) {
                break;
            } else {
                $tripPeriodHaltPoint = $tripPeriod->getDeparturePoint();
                $comparingTripPeriodHaltPoint = $comparingTripPeriod->getDeparturePoint();
                if ($tripPeriodHaltPoint == $comparingTripPeriodHaltPoint) {
                    array_push($caseOfConccurentlyHaltedBuses, $comparingTripPeriod);
                }
            }

            $x++;
        }

        return $caseOfConccurentlyHaltedBuses;
    }

}
