<?php

include_once 'TripVoucherXL.php';

class ExodusXL {

    private $number;
    private $tripVouchers;

    function __construct() {
        $this->tripVouchers = array();
    }

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
