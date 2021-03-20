<?php

require_once 'DAO/DataBaseTools.php';
require_once 'Model/TripPeriodDNA_XL.php';

class RouteDBController {

    private $dataBaseTools;

    function __construct() {
        $this->dataBaseTools = new DataBaseTools();
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
                            if ($tripPeriodType == "ბაზიდან გასვლა") {
                                $tripPeriodType .= "-" . $firstTripPeriodStartPoint;
                            }
                            if ($tripPeriodType == "ბაზაში დაბრუნება") {
                                $tripPeriodType = "$lastTripPeriodEndPoint-ბაზაში დაბრუნება";
                            }
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
        return $routes = $this->setTripPeriodDNAs($routes);
    }

}
