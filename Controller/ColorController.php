<?php

class ColorController {

    public function getIntervalColor($intervalDifferenceInSeconds) {
        if ($intervalDifferenceInSeconds > 60 && $intervalDifferenceInSeconds <= 300) {
            return "yellow";
        }if ($intervalDifferenceInSeconds > 300) {
            return "red";
        }
        return "white";
    }

}
