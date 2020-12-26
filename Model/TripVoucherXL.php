<?php

include_once 'TripPeriodXL.php';

class TripVoucherXL {

    private $number;
    private $busNumber;
    private $busType;
    private $driverNumber;
    private $driverName;
    private $notes;
    private $tripPeriods;

    function __construct() {
        $this->tripPeriods = array();
    }

    function getNumber() {
        return $this->number;
    }

    function getBusNumber() {
        return $this->busNumber;
    }

    function getBusType() {
        return $this->busType;
    }

    function getDriverNumber() {
        return $this->driverNumber;
    }

    function getDriverName() {
        return $this->driverName;
    }

    function getTripPeriods() {
        return $this->tripPeriods;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setBusNumber($busNumber) {
        $this->busNumber = $busNumber;
    }

    function setBusType($busType) {
        $this->busType = $busType;
    }

    function setDriverNumber($driverNumber) {
        $this->driverNumber = $driverNumber;
    }

    function setDriverName($driverName) {
        $this->driverName = $driverName;
    }

    function setTripPeriods($tripPeriods) {
        $this->tripPeriods = $tripPeriods;
    }

    function getNotes() {
        return $this->notes;
    }

    function setNotes($notes) {
        $this->notes = $notes;
    }


}
