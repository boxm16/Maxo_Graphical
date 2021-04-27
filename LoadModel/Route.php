<?php

require_once 'TripVoucher.php';

class Route {

    private $number;
    private $tripVouchers;

    function getNumber() {
        return $this->number;
    }

    function getTripVouchers() {
        return $this->tripVouchers;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setTripVouchers($tripVouchers) {
        $this->tripVouchers = $tripVouchers;
    }

}
