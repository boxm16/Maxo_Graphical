<?php

require_once 'Controller/TimeController.php';
$timeController = new TimeController();
echo $timeController->getSecondsFromTimeStamp("-00:00:10");
