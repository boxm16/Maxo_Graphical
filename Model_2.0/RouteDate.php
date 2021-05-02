<?php

class RouteDate {

    private $routeNumber;
    private $dates;

    function getRouteNumber(): String {
        return $this->routeNumber;
    }

    function getDates(): array {
        return $this->dates;
    }

    function setRouteNumber(String $routeNumber) {
        $this->routeNumber = $routeNumber;
    }

    function setDates(array $dates) {
        $this->dates = $dates;
    }

}
