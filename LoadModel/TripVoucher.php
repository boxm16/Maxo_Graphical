<?php
require_once 'TripPeriod.php';
class TripVoucher {

    private $number;
    private $routeNumber;
    private $dateStamp;
    private $exodusNumber;
    private $driverNumber;
    private $driverName;
    private $busNumber;
    private $busType;
    private $notes;
    private $tripPeriods;
    
    function getNumber() {
        return $this->number;
    }

    function getRouteNumber() {
        return $this->routeNumber;
    }

    function getDateStamp() {
        return $this->dateStamp;
    }

    function getExodusNumber() {
        return $this->exodusNumber;
    }

    function getDriverNumber() {
        return $this->driverNumber;
    }

    function getDriverName() {
        return $this->driverName;
    }

    function getBusNumber() {
        return $this->busNumber;
    }

    function getBusType() {
        return $this->busType;
    }

    function getNotes() {
        return $this->notes;
    }

    function getTripPeriods() {
        return $this->tripPeriods;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setRouteNumber($routeNumber) {
        $this->routeNumber = $routeNumber;
    }

    function setDateStamp($dateStamp) {
        $this->dateStamp = $dateStamp;
    }

    function setExodusNumber($exodusNumber) {
        $this->exodusNumber = $exodusNumber;
    }

    function setDriverNumber($driverNumber) {
        $this->driverNumber = $driverNumber;
    }

    function setDriverName($driverName) {
        $this->driverName = $driverName;
    }

    function setBusNumber($busNumber) {
        $this->busNumber = $busNumber;
    }

    function setBusType($busType) {
        $this->busType = $busType;
    }

    function setNotes($notes) {
        $this->notes = $notes;
    }

    function setTripPeriods($tripPeriods) {
        $this->tripPeriods = $tripPeriods;
    }



}
