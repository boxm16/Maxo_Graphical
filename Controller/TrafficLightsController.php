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
        if ($timeInSeconds < 61 && $timeInSeconds > -60) {
            return "white";
        }
        if ($timeInSeconds < 301 && $timeInSeconds > -300) {
            return "yellow";
        }
        return "red";
    }

}
