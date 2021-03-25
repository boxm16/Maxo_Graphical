<?php

class TripPeriodDataCarrier {

    private $routeNumber;
    private $abTripPeriodTimeStandart;
    private $abLowCount;
    private $abHighCount;
    private $abLowTotal;
    private $abHighTotal;
    private $baTripPeriodTimeStandart;
    private $baLowCount;
    private $baHighCount;
    private $baLowTotal;
    private $baHighTotal;

    function __construct() {
        $this->routeNumber = "";
        $this->abTripPeriodTimeStandart = "";
        $this->abLowCount = 0;
        $this->abHighCount = 0;
        $this->abLowTotal = 0;
        $this->abHighTotal = 0;
        $this->baTripPeriodTimeStandart = "";
        $this->baLowCount = 0;
        $this->baHighCount = 0;
        $this->baLowTotal = 0;
        $this->baHighTotal = 0;
    }

    function getRouteNumber() {
        return $this->routeNumber;
    }

    function getAbTripPeriodTimeStandart() {
        return $this->abTripPeriodTimeStandart;
    }

    function getAbLowCount() {
        return $this->abLowCount;
    }

    function getAbHighCount() {
        return $this->abHighCount;
    }

    function getAbLowTotal() {
        return $this->abLowTotal;
    }

    function getAbHighTotal() {
        return $this->abHighTotal;
    }

    function getBaTripPeriodTimeStandart() {
        return $this->baTripPeriodTimeStandart;
    }

    function getBaLowCount() {
        return $this->baLowCount;
    }

    function getBaHighCount() {
        return $this->baHighCount;
    }

    function getBaLowTotal() {
        return $this->baLowTotal;
    }

    function getBaHighTotal() {
        return $this->baHighTotal;
    }

    function setRouteNumber($routeNumber) {
        $this->routeNumber = $routeNumber;
    }

    function setAbTripPeriodTimeStandart($abTripPeriodTimeStandart) {
        $this->abTripPeriodTimeStandart = $abTripPeriodTimeStandart;
    }

    function setAbLowCount($abLowCount) {
        $this->abLowCount = $abLowCount;
    }

    function setAbHighCount($abHighCount) {
        $this->abHighCount = $abHighCount;
    }

    function setAbLowTotal($abLowTotal) {
        $this->abLowTotal = $abLowTotal;
    }

    function setAbHighTotal($abHighTotal) {
        $this->abHighTotal = $abHighTotal;
    }

    function setBaTripPeriodTimeStandart($baTripPeriodTimeStandart) {
        $this->baTripPeriodTimeStandart = $baTripPeriodTimeStandart;
    }

    function setBaLowCount($baLowCount) {
        $this->baLowCount = $baLowCount;
    }

    function setBaHighCount($baHighCount) {
        $this->baHighCount = $baHighCount;
    }

    function setBaLowTotal($baLowTotal) {
        $this->baLowTotal = $baLowTotal;
    }

    function setBaHighTotal($baHighTotal) {
        $this->baHighTotal = $baHighTotal;
    }

}
