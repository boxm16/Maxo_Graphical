<?php

class tripPeriodDNA {

    private $routeNumber;
    private $dateStamp;
    private $exodusNumber;
    private $voucherNumber;
    private $busNumber;
    private $busType;
    private $driverNumber;
    private $driverName;
    private $notes;

    function getVoucherNumber() {
        return $this->voucherNumber;
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

    function getNotes() {
        return $this->notes;
    }

    function getExodusNumber() {
        return $this->exodusNumber;
    }

    function getDateStamp() {
        return $this->dateStamp;
    }

    function getRouteNumber() {
        return $this->routeNumber;
    }

    function setVoucherNumber($voucherNumber) {
        $this->voucherNumber = $voucherNumber;
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

    function setNotes($notes) {
        $this->notes = $notes;
    }

    function setExodusNumber($exodusNumber) {
        $this->exodusNumber = $exodusNumber;
    }

    function setDateStamp($dateStamp) {
        $this->dateStamp = $dateStamp;
    }

    function setRouteNumber($routeNumber) {
        $this->routeNumber = $routeNumber;
    }

}
