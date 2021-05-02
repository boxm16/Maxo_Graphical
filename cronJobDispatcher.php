<?php

require_once 'Controller/CronJobController.php';




$cronJobController = new CronJobController();
if (isset($_GET["loadingStatusRequest"])) {

    if ($cronJobController->getLoadingStatus()) {
        echo "loading";
    } else {
        echo "ready";
    }
} else {
    if ($cronJobController->isLoading()) {

        echo "loading";
    } else {
        echo "ready";
    }
}
?>