<?php

include_once 'DayXL.php';

class RouteXL {

    private $number;
    private $aPoint;
    private $bPoint;
    private $days;

    function __construct() {
        $this->days = array();
    }

    function getNumber() {
        return $this->number;
    }

    function getDays() {
        return $this->days;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setDays($days) {
        $this->days = $days;
    }

    function getAPoint() {
        return $this->aPoint;
    }

    function getBPoint() {
        return $this->bPoint;
    }

    function setAPoint($aPoint) {
        $this->aPoint = $aPoint;
    }

    function setBPoint($bPoint) {
        $this->bPoint = $bPoint;
    }

}
