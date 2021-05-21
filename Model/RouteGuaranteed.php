<?php

require_once 'ExodusGuaranteed.php';

class RouteGuaranteed {

    private $number;
    private $baseNumber;
    private $scheme;
    private $busType;
    private $dateStamp;
    private $aPoint;
    private $bPoint;
    private $exoduses;
    private $ABTimeTable;
    private $BATimeTable;
    private $routeEndTime;

    function __construct() {
        $this->dateStamp = null;
        $this->exoduses = array();
        $this->ABTimeTable = array();
        $this->BATimeTable = array();
    }

    function getNumber() {
        return $this->number;
    }

    function getBaseNumber() {
        return $this->baseNumber;
    }

    function getScheme() {
        return $this->scheme;
    }

    function getBusType() {
        return $this->busType;
    }

    function getDateStamp() {
        return $this->dateStamp;
    }

    function getAPoint() {
        return $this->aPoint;
    }

    function getBPoint() {
        return $this->bPoint;
    }

    function getExoduses() {
        return $this->exoduses;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setBaseNumber($baseNumber) {
        $this->baseNumber = $baseNumber;
    }

    function setScheme($scheme) {
        $this->scheme = $scheme;
    }

    function setBusType($busType) {
        $this->busType = $busType;
    }

    function setDateStamp($dateStamp) {
        $this->dateStamp = $dateStamp;
    }

    function setAPoint($aPoint) {
        $this->aPoint = $aPoint;
    }

    function setBPoint($bPoint) {
        $this->bPoint = $bPoint;
    }

    function getABTimeTable() {
        return $this->ABTimeTable;
    }

    function getBATimeTable() {
        return $this->BATimeTable;
    }

    function setABTimeTable($ABTimeTable) {
        $this->ABTimeTable = $ABTimeTable;
    }

    function setBATimeTable($BATimeTable) {
        $this->BATimeTable = $BATimeTable;
    }

    function setExoduses($exoduses) {
        $this->exoduses = $exoduses;
    }

    function getRouteEndTime() {
        return $this->routeEndTime;
    }

    function setRouteEndTime($routeEndTime) {
        $this->routeEndTime = $routeEndTime;
    }

    //---//---//---//---//---//---//---//---//---//---//---//---
    public function getExodusesNumber() {
        return count($this->exoduses);
    }

    public function getRouteStartTime() {
        if (count($this->BATimeTable) > 0) {
            $abStartTime = $this->ABTimeTable[0];
            $baStartTime = $this->BATimeTable[0];
            return $abStartTime <= $baStartTime ? $abStartTime : $baStartTime;
        } else {
            return $this->ABTimeTable[0];
        }
    }

}
