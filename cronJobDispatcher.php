<?php

require_once 'Controller/CronJobController.php';


$cronJobController = new CronJobController();
if ($cronJobController->isLoading()) {
    echo "loading";
} else {
    echo "ready";
}
?>