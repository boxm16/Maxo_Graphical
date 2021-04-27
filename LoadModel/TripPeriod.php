<?php

class TripPeriod {
    
    private $tripVoucherNumber;
    private $type;
    private $startTimeScheduled;
    private $startTimeActual;
    private $startTimeDifference;
    private $arrivalTimeScheduled;
    private $arrivalTimeActual;
    private $arrivalTimeDifference;
    
    
    function __construct($tripVoucherNumber, $type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference) {
        $this->tripVoucherNumber = $tripVoucherNumber;
        $this->type = $type;
        $this->startTimeScheduled = $startTimeScheduled;
        $this->startTimeActual = $startTimeActual;
        $this->startTimeDifference = $startTimeDifference;
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
        $this->arrivalTimeActual = $arrivalTimeActual;
        $this->arrivalTimeDifference = $arrivalTimeDifference;
    }

    
    
    function getTripVoucherNumber() {
        return $this->tripVoucherNumber;
    }

    function getType() {
        return $this->type;
    }

    function getStartTimeScheduled() {
        return $this->startTimeScheduled;
    }

    function getStartTimeActual() {
        return $this->startTimeActual;
    }

    function getStartTimeDifference() {
        return $this->startTimeDifference;
    }

    function getArrivalTimeScheduled() {
        return $this->arrivalTimeScheduled;
    }

    function getArrivalTimeActual() {
        return $this->arrivalTimeActual;
    }

    function getArrivalTimeDifference() {
        return $this->arrivalTimeDifference;
    }

    function setTripVoucherNumber($tripVoucherNumber) {
        $this->tripVoucherNumber = $tripVoucherNumber;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setStartTimeScheduled($startTimeScheduled) {
        $this->startTimeScheduled = $startTimeScheduled;
    }

    function setStartTimeActual($startTimeActual) {
        $this->startTimeActual = $startTimeActual;
    }

    function setStartTimeDifference($startTimeDifference) {
        $this->startTimeDifference = $startTimeDifference;
    }

    function setArrivalTimeScheduled($arrivalTimeScheduled) {
        $this->arrivalTimeScheduled = $arrivalTimeScheduled;
    }

    function setArrivalTimeActual($arrivalTimeActual) {
        $this->arrivalTimeActual = $arrivalTimeActual;
    }

    function setArrivalTimeDifference($arrivalTimeDifference) {
        $this->arrivalTimeDifference = $arrivalTimeDifference;
    }


    
    
}
