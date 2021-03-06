<?php

require_once "SimpleXLSX.php";
require_once 'Model/RouteXL.php';
require_once 'Model/TripPeriodDNA_XL.php';
require_once 'TimeCalculator.php';
require_once 'Model/RouteGuaranteed.php';

class RouteXLController {

    private $routes; //this is array of routes

    public function getFullRoutes($clientId) {
        $excelRows = $this->readExcelFile($clientId);
        $fullRoutes = $this->getRoutesFromExcelRows($excelRows); //array of routes
        return $fullRoutes;
    }

    private function readExcelFile($clientId) {
        if ($xlsx = SimpleXLSX::parse("uploads/routeExcelFile" . $clientId . ".xlsx")) {
            $rows = $xlsx->rowsEx();
        } else {
            header("Location:excelFileErrorPage.php");
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
        $routes = $this->setTripPeriodDNAs($routes);

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
            $notes = $row[28]["value"];

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
        $tripPeriodTypeStampInRowCell = $row[15]["value"];
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

        if ($row[16]["value"] != "") {
            $tripPeriodType = "baseLeaving_A";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriodType = "baseLeaving_B";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBaseReturnPeriod($row) {

        if ($row[16]["value"] != "") {
            $tripPeriodType = "A_baseReturn";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriodType = "B_baseReturn";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBreakPeriod($row) {
        $tripPeriodType = "break";
        if ($row[16]["value"] != "") {
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createTripPeridsOfRound($row) {
        $tripPeriodsOfRound = array();
        if ($row[16]["value"] != "" && $row[22]["value"] != "") {
            $leftSideTime = $this->time24($row[16]["value"]);
            $rightSideTime = $this->time24($row[22]["value"]);
            $timeCalculator = new TimeCalculator();
            $leftSideTimeInSeconds = $timeCalculator->getSecondsFromTimeStamp($leftSideTime);
            $rightSideTimeInSeconds = $timeCalculator->getSecondsFromTimeStamp($rightSideTime);
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
        if ($row[16]["value"] != "") {
            $tripPeriodType = "ab";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        if ($row[22]["value"] != "") {
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

        $startTimeScheduled = $this->time24($row[16]["value"]);
        $startTimeActual = $this->time24($row[17]["value"]);
        $startTimeDifference = $row[18]["value"];
        $arrivalTimeScheduled = $this->time24($row[19]["value"]);
        $arrivalTimeActual = $this->time24($row[20]["value"]);
        $arrivalTimeDifference = $row[21]["value"];
        $tripPeriod = new TripPeriodXL($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
        return $tripPeriod;
    }

    private function createTripPeriodFromRightSide($row, $type) {
        $startTimeScheduled = $this->time24($row[22]["value"]);
        $startTimeActual = $this->time24($row[23]["value"]);
        $startTimeDifference = $row[24]["value"];
        $arrivalTimeScheduled = $this->time24($row[25]["value"]);
        $arrivalTimeActual = $this->time24($row[26]["value"]);
        $arrivalTimeDifference = $row[27]["value"];
        $tripPeriod = new TripPeriodXL($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
        return $tripPeriod;
    }

    private function addPreviosTripPeriodTimes($tripPeriod, $tripPeriods) {

        $previousTripPeriod = $tripPeriods[count($tripPeriods) - 1];
        $previousTripPeriodArrivalTimeActual = $previousTripPeriod->getArrivalTimeActual();
        $previousTripPeriodArrivalTimeScheduled = $previousTripPeriod->getArrivalTimeScheduled();
        $tripPeriod->setPreviousTripPeriodArrivalTimeActual($previousTripPeriodArrivalTimeActual);
        $tripPeriod->setPreviousTripPeriodArrivalTimeScheduled($previousTripPeriodArrivalTimeScheduled);
        return $tripPeriod;
    }

    private function setTripPeriodDNAs($routes) {
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();
            $days = $route->getDays();
            foreach ($days as $day) {
                $dateStamp = $day->getDateStamp();
                $exoduses = $day->getExoduses();
                foreach ($exoduses as $exodus) {
                    $exodusNumber = $exodus->getNumber();
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {
                        $tripVoucherNumber = $tripVoucher->getNumber();
                        $busNumber = $tripVoucher->getBusNumber();
                        $busType = $tripVoucher->getBusType();
                        $driverNumber = $tripVoucher->getDriverNumber();
                        $driverName = $tripVoucher->getDriverName();
                        $notes = $tripVoucher->getNotes();
                        $tripPeriods = $tripVoucher->getTripPeriods();

                        foreach ($tripPeriods as $tripPeriod) {
                            $tripPeriodDNA = new tripPeriodDNA();
                            $tripPeriodDNA->setRouteNumber($routeNumber);
                            $tripPeriodDNA->setDateStamp($dateStamp);
                            $tripPeriodDNA->setExodusNumber($exodusNumber);
                            $tripPeriodDNA->setBusNumber($busNumber);
                            $tripPeriodDNA->setBusType($busType);
                            $tripPeriodDNA->setVoucherNumber($tripVoucherNumber);
                            $tripPeriodDNA->setDriverNumber($driverNumber);
                            $tripPeriodDNA->setDriverName($driverName);
                            $tripPeriodDNA->setNotes($notes);
                            $tripPeriod->setTripPeriodDNA($tripPeriodDNA);
                        }
                    }
                }
            }
        }


        return $routes;
    }

    private function time24($shortTimeStamp) {
        if ($shortTimeStamp == "") {
            return $shortTimeStamp;
        }
        $splittedTime = explode(":", $shortTimeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        $totalSeconds = ($hours * 60 * 60) + ($minutes * 60);
        if ($totalSeconds > 3 * 60 * 60) {
            return $shortTimeStamp;
        } else {
            $totalSeconds += 24 * 60 * 60;
            $h = floor($totalSeconds / 3600);
            $m = floor(($totalSeconds - ($h * 3600)) / 60);

            return sprintf('%02d', $h) . ":" . sprintf('%02d', $m);
        }
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

    public function getSiftedRoutes($clientId, $requestedRoutesAndDates) {

        $requestedRoutesAndDatesSeparated = $this->separetRoutesAndDates($requestedRoutesAndDates);

        $fullRoutes = $this->getFullRoutes($clientId);
        $returnArray = array();
        foreach ($fullRoutes as $route) {
            $routeNumber = $route->getNumber();
            if (array_key_exists($routeNumber, $requestedRoutesAndDatesSeparated)) {
                $requestedDates = $requestedRoutesAndDatesSeparated[$routeNumber];

                $days = $route->getDays();
                $cloneRouteDays = array();
                foreach ($days as $day) {
                    $dateStamp = $day->getDateStamp();
                    if (in_array($dateStamp, $requestedDates)) {
                        array_push($cloneRouteDays, $day);
                    }
                }
                $cloneRoute = new RouteXL();
                $cloneRoute->setNumber($routeNumber);
                $cloneRoute->setDays($cloneRouteDays);
                array_push($returnArray, $cloneRoute);
            }
        }
        return $returnArray;
    }

    private function separetRoutesAndDates($requestedRoutesAndDates) {
        $arrayOfDates = explode(",", $requestedRoutesAndDates);
        $routesAndDates = array();
        foreach ($arrayOfDates as $item) {
            if ($item != "") {//last $item is epmty, until i fix it, i leave this as is its
                $routeAndDate = explode(":", $item);

                $routeNumber = $routeAndDate[0];
                $date = $routeAndDate[1];
                if (array_key_exists("$routeNumber", $routesAndDates)) {
                    $dates = $routesAndDates["$routeNumber"];
                    array_push($dates, $date);
                    $routesAndDates["$routeNumber"] = $dates;
                } else {
                    $dates = array($date);
                    $routesAndDates["$routeNumber"] = $dates;
                }
            }
        }
        return $routesAndDates;
    }

    public function getRoutesDelailedPackage($clientId, $requestedRoutesAndDates) {

        $routes = $this->getSiftedRoutes($clientId, $requestedRoutesAndDates);

        $startTimeScheduledPackage = array();
        $startTimeActualPackage = array();
        $startTimeDifferencePackage = array();
        $tripPeriodTypePackage = array();
        $arrivalTimeScheduledPackage = array();
        $arrivalTimeActualPackage = array();
        $arrivalTimeDifferencePackage = array();
        $tripPeriodScheduledPackage = array();
        $tripPeriodActualPackage = array();
        $haltTimeScheduledPackage = array();
        $haltTimeActualPackage = array();
        $lostTimePackage = array();

        foreach ($routes as $route) {

            $days = $route->getDays();
            foreach ($days as $day) {
                $exoduses = $day->getExoduses();
                foreach ($exoduses as $exodus) {
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {
                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {
                            $startTimeScheduledPackage[$tripPeriod->getStartTimeScheduled()] = true;
                            $startTimeActualPackage[$tripPeriod->getStartTimeActual()] = true;
                            $startTimeDifferencePackage[$tripPeriod->getStartTimeDifference()] = true;
                            $tripPeriodTypePackage[$tripPeriod->getTypeGe()] = true;
                            $arrivalTimeScheduledPackage[$tripPeriod->getArrivalTimeScheduled()] = true;
                            $arrivalTimeActualPackage[$tripPeriod->getArrivalTimeActual()] = true;
                            $arrivalTimeDifferencePackage[$tripPeriod->getArrivalTimeDifference()] = true;
                            $tripPeriodScheduledPackage[$tripPeriod->getTripPeriodScheduledTime()] = true;
                            $tripPeriodActualPackage[$tripPeriod->getTripPeriodActualTime()] = true;
                            $haltTimeScheduledPackage[$tripPeriod->getHaltTimeScheduled()] = true;
                            $haltTimeActualPackage[$tripPeriod->getHaltTimeActual()] = true;
                            $lostTimePackage[$tripPeriod->getLostTime()] = true;
                        }
                    }
                }
            }
        }
        ksort($startTimeScheduledPackage);
        ksort($startTimeActualPackage);
        ksort($startTimeDifferencePackage);
        ksort($tripPeriodTypePackage);
        ksort($arrivalTimeScheduledPackage);
        ksort($arrivalTimeActualPackage);
        ksort($arrivalTimeDifferencePackage);
        ksort($tripPeriodScheduledPackage);
        ksort($tripPeriodActualPackage);
        ksort($haltTimeScheduledPackage);
        ksort($haltTimeActualPackage);
        ksort($lostTimePackage);
        $routesDetailedPackage["routes"] = $routes;
        $routesDetailedPackage["startTimeActualPackage"] = $startTimeActualPackage;
        $routesDetailedPackage["startTimeScheduledPackage"] = $startTimeScheduledPackage;
        $routesDetailedPackage["startTimeDifferencePackage"] = $startTimeDifferencePackage;
        $routesDetailedPackage["tripPeriodTypePackage"] = $tripPeriodTypePackage;
        $routesDetailedPackage["arrivalTimeScheduledPackage"] = $arrivalTimeScheduledPackage;
        $routesDetailedPackage["arrivalTimeActualPackage"] = $arrivalTimeActualPackage;
        $routesDetailedPackage["arrivalTimeDifferencePackage"] = $arrivalTimeDifferencePackage;
        $routesDetailedPackage["tripPeriodScheduledPackage"] = $tripPeriodScheduledPackage;
        $routesDetailedPackage["tripPeriodActualPackage"] = $tripPeriodActualPackage;
        $routesDetailedPackage["haltTimeScheduledPackage"] = $haltTimeScheduledPackage;
        $routesDetailedPackage["haltTimeActualPackage"] = $haltTimeActualPackage;
        $routesDetailedPackage["lostTimePackage"] = $lostTimePackage;
        return $routesDetailedPackage;
    }

    public function getExcelFormPackage($clientId, $requestedRoutesAndDates) {

        $routes = $this->getSiftedRoutes($clientId, $requestedRoutesAndDates);

        $routeNumberPackage = array();
        $dateStampPackage = array();
        $busNumberPackage = array();
        $exodusNumberPackage = array();
        $driverNamePackage = array();
        $tripPeriodTypePackage = array();
        $startTimeScheduledPackage = array();
        $startTimeActualPackage = array();
        $arrivalTimeScheduledPackage = array();
        $arrivalTimeActualPackage = array();
        $tripPeriodScheduledPackage = array();
        $tripPeriodActualPackage = array();
        $tripPeriodDifferenceTimePackage = array();

        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();
            $days = $route->getDays();
            foreach ($days as $day) {
                $dateStamp = $day->getDateStamp();
                $exoduses = $day->getExoduses();
                foreach ($exoduses as $exodus) {
                    $exodusNumber = $exodus->getNumber();
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {
                        $busNumber = $tripVoucher->getBusNumber();
                        $driverName = $tripVoucher->getDriverName();
//this part is for setting "nulovani ciris" meore punkti
                        $firstTripPeriodStartPoint = $tripVoucher->getFirstTripPeriodStartPoint();
                        $lastTripPeriodEndPoint = $tripVoucher->getLastTripPeriodEndPoint();


                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {
                            $routeNumberPackage[$routeNumber] = true;
                            $dateStampPackage[$dateStamp] = true;
                            $busNumberPackage[$busNumber] = true;
                            $exodusNumberPackage[$exodusNumber] = true;
                            $driverNamePackage[$driverName] = true;

                            $tripPeriodType = $tripPeriod->getTypeGe();

                            $tripPeriodTypePackage[$tripPeriodType] = true;

                            $startTimeScheduledPackage[$tripPeriod->getStartTimeScheduled()] = true;
                            $startTimeActualPackage[$tripPeriod->getStartTimeActual()] = true;
                            $startTimeDifferencePackage[$tripPeriod->getStartTimeDifference()] = true;

                            $arrivalTimeScheduledPackage[$tripPeriod->getArrivalTimeScheduled()] = true;
                            $arrivalTimeActualPackage[$tripPeriod->getArrivalTimeActual()] = true;

                            $tripPeriodScheduledPackage[$tripPeriod->getTripPeriodScheduledTime()] = true;
                            $tripPeriodActualPackage[$tripPeriod->getTripPeriodActualTime()] = true;
                            $tripPeriodDifferenceTimePackage[$tripPeriod->getTripPeriodDifferenceTime()] = true;




                            $startTimeScheduledPackage[$tripPeriod->getStartTimeScheduled()] = true;
                        }
                    }
                }
            }
        }


        ksort($routeNumberPackage);
        ksort($dateStampPackage);
        ksort($busNumberPackage);
        ksort($exodusNumberPackage);
        ksort($driverNamePackage);
        //  ksort($tripPeriodTypePackage);
        ksort($startTimeScheduledPackage);
        ksort($startTimeActualPackage);
        ksort($arrivalTimeScheduledPackage);
        ksort($arrivalTimeActualPackage);
        ksort($tripPeriodScheduledPackage);
        ksort($tripPeriodActualPackage);
        ksort($tripPeriodDifferenceTimePackage);


        $excelFormPackage["routes"] = $routes;
        $excelFormPackage["routeNumberPackage"] = $routeNumberPackage;
        $excelFormPackage["dateStampPackage"] = $dateStampPackage;
        $excelFormPackage["busNumberPackage"] = $busNumberPackage;
        $excelFormPackage["exodusNumberPackage"] = $exodusNumberPackage;
        $excelFormPackage["driverNamePackage"] = $driverNamePackage;
        $excelFormPackage["tripPeriodTypePackage"] = $tripPeriodTypePackage;

        $excelFormPackage["startTimeActualPackage"] = $startTimeActualPackage;
        $excelFormPackage["startTimeScheduledPackage"] = $startTimeScheduledPackage;


        $excelFormPackage["arrivalTimeScheduledPackage"] = $arrivalTimeScheduledPackage;
        $excelFormPackage["arrivalTimeActualPackage"] = $arrivalTimeActualPackage;

        $excelFormPackage["tripPeriodScheduledPackage"] = $tripPeriodScheduledPackage;
        $excelFormPackage["tripPeriodActualPackage"] = $tripPeriodActualPackage;
        $excelFormPackage["tripPeriodDifferenceTimePackage"] = $tripPeriodDifferenceTimePackage;

        return $excelFormPackage;
    }

    public function UploadRouteNames() {

        if ($xlsx = SimpleXLSX::parse("uploads/RouteNamesAndSchemes.xlsx")) {
            $rows = $xlsx->rowsEx();
            //reading part  
            $routes = array();
            for ($x = 1; $x < count($rows); $x++) {
                $row = $rows[$x];
                $routeNumber = $row[2]["value"];
                $routeScheme = str_replace('"', '\'', $row[3]["value"]);
                $aPoint = str_replace('"', '\'', $row[4]["value"]);
                $bPoint = str_replace('"', '\'', $row[5]["value"]);
                $route = new RouteGuaranteed();
                $route->setNumber($routeNumber);
                $route->setScheme($routeScheme);
                $route->setAPoint($aPoint);
                $route->setBPoint($bPoint);
                array_push($routes, $route);
            }
            return $routes;
        } else {
            header("Location:errorPage.php");
            echo "ფაილი არ არის ატვირთული ან დაზიანებულია(" . SimpleXLSX::parseError() . ")";
            echo "<hr>";
            return;
        }
    }

}
