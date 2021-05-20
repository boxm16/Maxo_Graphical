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

    function __construct() {
        $this->dateStamp = null;
        $this->exoduses = array();
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

    function setExoduses($exoduses) {
        $this->exoduses = $exoduses;
    }

}
