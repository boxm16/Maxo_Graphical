<?php

require_once 'Controller/RouteXLController.php';
require_once 'Controller/ExcelExportController.php';

require_once 'clientId.php';
session_start();
if (isset($_POST["routes:dates"])) {
    $_SESSION["routes:dates"] = $_POST["routes:dates"];
    $requestedRoutesAndDates = $_POST["routes:dates"];
} else {
    if (isset($_SESSION["routes:dates"])) {

        $requestedRoutesAndDates = $_SESSION["routes:dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}

$routeController = new RouteXLController();
$routes = $routeController->getSiftedRoutes($clientId, $requestedRoutesAndDates);

$excelExportController = new ExcelExportController();


//here starts dispatcher part

if (isset($_POST["guaranteed"])) {
    $excelExportController->exportGuaranteedTripPeriods($routes);
}

if (isset($_POST["guaranteedNew"])) {
    $excelExportController->exportGuaranteedTripPeriodsNewVersion($routes);
}


if (isset($_POST["excelForm"])) {
    $requestedData = $_POST;
    $excelExportController->exportExcelForm($routes, $requestedData);
}
