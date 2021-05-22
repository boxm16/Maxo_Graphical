<?php

class TimeCalculator {

    public function getSecondsFromTimeStamp($timeStamp) {
        $splittedTime = explode(":", $timeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        if (count($splittedTime) == 3) {
            $seconds = $splittedTime[2];
        } else {
            $seconds = 0;
        }

        if ($hours === "-00") {
            return $totalSeconds = -1 * (($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1));
        }
        if ($hours < 0) {
            $hours = -1 * $hours;
            return $totalSeconds = -1 * (($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1));
        } else {
            return $totalSeconds = ($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1);
        }
    }

    public function getTimeStampFromSeconds($seconds) {
        if ($seconds >= 0) {
            return gmdate("H:i:s", $seconds);
        } else {
            $seconds = -1 * $seconds;

            return "-" . gmdate("H:i:s", $seconds);
        }
    }

    public function getTimeStampFromSecondsShortVersion($seconds) {
        if ($seconds >= 0) {
            return gmdate("i:s", $seconds);
        } else {
            $seconds = -1 * $seconds;

            return "-" . gmdate("i:s", $seconds);
        }
    }

    public function getTimeStampsDifference($timeStamp_1, $timeStamp_2) {
        $seconds_1 = $this->getSecondsFromTimeStamp($timeStamp_1);
        $seconds_2 = $this->getSecondsFromTimeStamp($timeStamp_2);
        $seconds = $seconds_1 - $seconds_2;

        return $this->getTimeStampFromSeconds($seconds);
    }

}
