<?php

require_once 'DAO/DataBaseTools.php';
require_once 'Model/TripPeriodDNA_XL.php';
require_once 'Controller/TimeCalculator.php';

class RouteDBController {

    private $dataBaseTools;
    private $timeCalculator;

    function __construct() {
        $this->dataBaseTools = new DataBaseTools();
        $this->timeCalculator = new TimeCalculator();
    }

    public function getRequestedRoutesAndDates($requestedRoutesAndDates) {

        $routes = $this->dataBaseTools->getRequestedRoutesAndDates($requestedRoutesAndDates);
        return $routes = $this->setTripPeriodDNAs($routes);
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

    public function getExcelFormPackage($requestedRoutesAndDates) {



        $routes = $this->getRequestedRoutesAndDates($requestedRoutesAndDates);

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

    public function getRouteForExodus($routeNumber, $dateStamp, $exodusNumber) {

        $routes = $this->dataBaseTools->getRouteForExodus($routeNumber, $dateStamp, $exodusNumber);
        return $this->setTripPeriodDNAs($routes);
    }

    public function getRouteForDay($routeNumber, $dateStamp) {

        $routes = $this->dataBaseTools->getRouteForDay($routeNumber, $dateStamp);
        return $this->setTripPeriodDNAs($routes);
    }

    public function getExcelFormFilterPackage($requestedRoutesAndDates, $filterData) {
        $masterFilter = $filterData["masterFilter"];
        unset($filterData["masterFilter"]);

        if (isset($_SESSION["originalFilterData"])) {
            $originalFilterData = $_SESSION["originalFilterData"];
            $newFilterData = $this->convertFilterData($filterData);
            $originalFilterData = $this->recheckOriginalFilterData($originalFilterData, $newFilterData);
        } else {
            $originalFilterData = $this->convertFilterData($filterData);
            $_SESSION["originalFilterData"] = $originalFilterData;
        }
        $routes = $this->getRequestedRoutesAndDates($requestedRoutesAndDates);



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
                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {


                            $routeNumberPackage[$routeNumber] = "true";
//$dateStampPackage[$dateStamp] = $newFilterData["dateStamp"][$dateStamp];
//$busNumberPackage[$busNumber] = $newFilterData["busNumber"][$busNumber];
//$exodusNumberPackage[$exodusNumber] = $newFilterData["exodusNumber"][$exodusNumber];
//$driverNamePackage[$driverName] = $newFilterData["driverName"][$driverName];

                            $tripPeriodType = $tripPeriod->getTypeGe();

//$tripPeriodTypePackage[$tripPeriodType] = $newFilterData["tripPeriodType"][$tripPeriodType];
//$startTimeScheduledPackage[$tripPeriod->getStartTimeScheduled()] = $newFilterData["startTimeScheduled"][$tripPeriod->getStartTimeScheduled()];
//$startTimeActualPackage[$tripPeriod->getStartTimeActual()] = $newFilterData["startTimeActual"][$tripPeriod->getStartTimeActual()];
//  $arrivalTimeScheduledPackage[$tripPeriod->getArrivalTimeScheduled()] = $newFilterData["arrivalTimeScheduled"][$tripPeriod->getArrivalTimeScheduled()];
// $arrivalTimeActualPackage[$tripPeriod->getArrivalTimeActual()] = $newFilterData["arrivalTimeActual"][$tripPeriod->getArrivalTimeActual()];
//$tripPeriodScheduledPackage[$tripPeriod->getTripPeriodScheduledTime()] = $newFilterData["tripPeriodScheduled"][$tripPeriod->getTripPeriodScheduledTime()];
//$tripPeriodActualPackage[$tripPeriod->getTripPeriodActualTime()] = $newFilterData["tripPeriodActual"][$tripPeriod->getTripPeriodActualTime()];
//$tripPeriodDifferenceTimePackage[$tripPeriod->getTripPeriodDifferenceTime()] = $newFilterData["tripPeriodDifference"][$tripPeriod->getTripPeriodDifferenceTime()];
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

    private function convertFilterData($filterData): array {
        $returnArray = array();
        foreach ($filterData as $key => $value) {
            $arrayedValue = $this->arrayString($value);
            $returnArray[$key] = $arrayedValue;
        }
        return $returnArray;
    }

    private function arrayString(string $string): array {
        $returnArray = array();
        $p = explode(",", $string);
        foreach ($p as $item) {
            if ($item != "") {
                $dp = explode("=", $item);
                $returnArray[$dp[0]] = $dp[1];
            }
        }
        return $returnArray;
    }

    private function recheckOriginalFilterData($originalFilterData, $newFilterData) {



        foreach ($newFilterData as $dataName => $column) {
            foreach ($column as $itemName => $itemValue) {
                $originalFilterData[$dataName][$itemName] = $newFilterData[$dataName][$itemName];
            }
        }
        return $originalFilterData;
    }

    public function getRoutePoints() {
        return $this->dataBaseTools->getRoutePoints();
    }

    public function changeRouteNames($routeNumber, $aPoint, $bPoint) {
        $this->dataBaseTools->changeRouteNames($routeNumber, $aPoint, $bPoint);
    }

    public function getRequestedTripPeriods($routeNumber, $dateStampsString, $type, $percents, $height) {
        $requestedTripPeriods = array();
        $dateStampsArray = explode(",", $dateStampsString);
        $requestedRoutesAndDates = "";
        foreach ($dateStampsArray as $dateStamp) {
            if ($dateStamp != "") {
                $item = $routeNumber . ":" . $dateStamp;
                $requestedRoutesAndDates .= $item . ",";
            }
        }
        $routes = $this->dataBaseTools->getRequestedRoutesAndDates($requestedRoutesAndDates);
        $routes = $this->setTripPeriodDNAs($routes);
        foreach ($routes as $route) {
            $days = $route->getDays();
            foreach ($days as $day) {
                $exoduses = $day->getExoduses();
                foreach ($exoduses as $exodus) {
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {
                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {
                            $tripPeriodType = $tripPeriod->getType();
                            if ($tripPeriodType == $type) {
                                $tripPeriodScheduledTime = $tripPeriod->getTripPeriodScheduledTime();
                                $tripPeriodActualTime = $tripPeriod->getTripPeriodActualTime();
                                if ($tripPeriodActualTime != "") {
                                    if ($height == "low") {
                                        if ($this->lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents)) {
                                            array_push($requestedTripPeriods, $tripPeriod);
                                        }
                                    }
                                    if ($height == "high") {
                                        if ($this->highPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents)) {
                                            array_push($requestedTripPeriods, $tripPeriod);
                                        }
                                    }
                                    if ($height == "both") {
                                        if ($this->lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents) || $this->highPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents)) {
                                            array_push($requestedTripPeriods, $tripPeriod);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return $requestedTripPeriods;
    }

//same function i have in ExcelExportController
    private function lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents) {

        $tripPeriodScheduledTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodScheduledTime);
        $tripPeriodActualTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime);
        $difference = $tripPeriodScheduledTimeInSeconds - $tripPeriodActualTimeInSeconds;
        if ($difference >= ($tripPeriodScheduledTimeInSeconds / 100) * (-1 * $percents) &&
                $difference < 0) {
            return true;
        } else {
            return false;
        }
    }

    //same function i have in ExcelExportController
    private function highPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents) {

        $tripPeriodScheduledTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodScheduledTime);
        $tripPeriodActualTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime);
        $difference = $tripPeriodScheduledTimeInSeconds - $tripPeriodActualTimeInSeconds;
        if ($difference <= ($tripPeriodScheduledTimeInSeconds / 100) * $percents &&
                $difference >= 0) {
            return true;
        } else {
            return false;
        }
    }

}
