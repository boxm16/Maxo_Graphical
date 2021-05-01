<?php

require_once 'Controller/CronJobController.php';




$cronJobController = new CronJobController();
if (isset($_GET["statusRequest"])) {

    if ($cronJobController->getLoadingMode()) {
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