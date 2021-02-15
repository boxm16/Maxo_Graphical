<?php

require_once 'TimeCalculator.php';

class TrafficLightsController {

    private $timeCalculator;

    function __construct() {
        $this->timeCalculator = new TimeCalculator();
    }

    public function getLightsForStandartTraffic($timeStamp) {
        if ($timeStamp == "") {
            return "white";
        }

        $timeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($timeStamp);
        if ($timeInSeconds < 61 && $timeInSeconds > -61) {
            return "white";
        }
        if ($timeInSeconds < 301 && $timeInSeconds > -301) {
            return "yellow";
        }
        return "red";
    }

    public function getLightsForRedWhiteTraffic($timeStamp) {
        if ($timeStamp == "") {
            return "white";
        }

        $timeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($timeStamp);

        if ($timeInSeconds < 601 && $timeInSeconds > -600) {
            return "white";
        }
        return "red";
    }

}
