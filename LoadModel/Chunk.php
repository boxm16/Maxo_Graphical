<?php

class Chunk {

    private $routes;
    private $tripVouchers;
    private $tripVouchersDoubler;
    private $tripPeriods;

    function __construct() {
        $this->routes = array();
        $this->tripVouchers = array();
        $this->tripVouchersDoubler=array();
        $this->tripPeriods = array();
    }

    function getRoutes() {
        return $this->routes;
    }

    function getTripVouchers() {
        return $this->tripVouchers;
    }

    function getTripPeriods() {
        return $this->tripPeriods;
    }

    function setRoutes($routes) {
        $this->routes = $routes;
    }

    function setTripVouchers($tripVouchers) {
        $this->tripVouchers = $tripVouchers;
    }

    function setTripPeriods($tripPeriods) {
        $this->tripPeriods = $tripPeriods;
    }

    function getTripVouchersDoubler() {
        return $this->tripVouchersDoubler;
    }

    function setTripVouchersDoubler($tripVouchersDoubler) {
        $this->tripVouchersDoubler = $tripVouchersDoubler;
    }


}
