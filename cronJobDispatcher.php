<?php

require_once 'Controller/CronJobController.php';
require_once 'Controller_2.0/ReportController.php';



$cronJobController = new CronJobController();
$reportController = new ReportController();
if (isset($_GET["loadingStatusRequest"])) {

    if ($cronJobController->getLoadingStatus()) {
        echo "loading";
    } else {
        echo "ready";
    }
} else {
    if ($cronJobController->isLoading()) {

        echo "loading";
    }$id = $cronJobController->isCreatingRouteDetailsReport();
    if ($id != null) {
        echo $id."<br>";
        echo "creating Route Details Report<br>";
        $reportController->createRouteDetailsReport($id);
    } else {
        echo "ready";
    }
}
?>