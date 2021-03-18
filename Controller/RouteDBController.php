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

}
