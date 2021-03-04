<?php

require_once 'Controller/RouteXLController.php';
session_start();
if (isset($_POST["routeNumber"])) {
    $_SESSION["routeNumber"] = $_POST["routeNumber"];
    $selectedRouteNumber = $_POST["routeNumber"];
    if (isset($_POST["dates"])) {
        $_SESSION["dates"] = $_POST["dates"];
        $selectedDates = $_POST["dates"];
    } else {
        $emptyDates = array();
        $_SESSION["dates"] = $emptyDates;
        $selectedDates = $emptyDates;
    }
} else {
    if (isset($_SESSION["routeNumber"]) && isset($_SESSION["dates"])) {

        $selectedRouteNumber = $_SESSION["routeNumber"];
        $selectedDates = $_SESSION["dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}
$routeController = new RouteXLController();
$routes = $routeController->getSiftedRoutes($selectedRouteNumber, $selectedDates);
$arrayOfDaters = explode(",", $_POST["routes:dates"]);
foreach($arrayOfDaters as $date){
    echo $date."<br>";
}
