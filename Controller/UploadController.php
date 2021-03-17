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
        $vouchersForDeletion = array();
        foreach ($xlRoutes as $route) {
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

        $this->dataBaseTools->deleteVouchers($vouchersForDeletion);
        $this->dataBaseTools->insertUploadedData($xlRoutes);
    }

}
