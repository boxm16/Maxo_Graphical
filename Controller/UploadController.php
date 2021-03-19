<?php

require_once 'DAO/DataBaseTools.php';
require_once 'RouteXLController.php';

class UploadController {

    private $dataBaseTools;
    private $routeController;

    function __construct() {
        $this->dataBaseTools = new DataBaseTools();
        $this->routeController = new RouteXLController();
    }

    public function saveUploadedDataIntoDatabase($clientId) {

        $xlRoutes = $this->routeController->getFullRoutes($clientId);
        $dbRoutes = $this->dataBaseTools->getRouteNumbers();

        $routeNumbersForInsertion = array();
        $vouchersForDeletion = array();
        var_dump($dbRoutes);
        foreach ($xlRoutes as $route) {

            $routeNumber = $route->getNumber();

            if (in_array($routeNumber, $dbRoutes)) {
                
            } else {

                array_push($routeNumbersForInsertion, $routeNumber);
            }

            $days = $route->getDays();
            foreach ($days as $day) {
                $exoduses = $day->getExoduses();
                foreach ($exoduses as $exodus) {
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {
                        $tripVoucherNumber = $tripVoucher->getNumber();
                        array_push($vouchersForDeletion, $tripVoucherNumber);
                    }
                }
            }
        }
        if (count($routeNumbersForInsertion) > 0) {
            $insertData = array();
            foreach ($routeNumbersForInsertion as $routeNumber) {

                $exploded = explode("-", $routeNumber);
                $prefix = $exploded[0];
                $suffix = null;
                if (count($exploded) > 1) {
                    $suffix = $exploded[1];
                }
                $insertRow = array($routeNumber, $prefix, $suffix, "A-პუნკტი", "B-პუნკტი");
                array_push($insertData, $insertRow);
            }
            $this->dataBaseTools->insertRoutes($insertData);
        }

        $this->dataBaseTools->deleteVouchers($vouchersForDeletion);
        $this->dataBaseTools->insertUploadedData($xlRoutes);
    }

}
