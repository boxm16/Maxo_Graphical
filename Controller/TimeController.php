<?php

class TimeController {

    public function getSecondsFromTimeStamp($timeStamp) {
        $splittedTime = explode(":", $timeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        if (count($splittedTime) == 3) {
            $seconds = $splittedTime[2];
        } else {
            $seconds = 0;
        }
        $totalSeconds = ($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1);
        return $totalSeconds;
    }

}
