<?php
require_once 'Controller/RouteXLController.php';
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
$s = microtime(true);

$routeController = new RouteXLController();
$routes = $routeController->getSiftedRoutes($clientId, $requestedRoutesAndDates);
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset = "UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        foreach ($routes as $routeNumber => $route) {

            $days = $route->getDays();
            foreach ($days as $day) {
                $dateStamp = $day->getDateStamp();
                $lastTrips = $day->getLastTrips();
                $ab_lastTripPeriodScheduled = $lastTrips["ab_lastTripPeriodScheduled"];
                $ba_lastTripPeriodScheduled = $lastTrips["ba_lastTripPeriodScheduled"];
                $ab_lastTripPeriodActual = $lastTrips["ab_lastTripPeriodActual"];
                $ba_lastTripPeriodActual = $lastTrips["ba_lastTripPeriodActual"];
                echo $dateStamp;
                echo "<br>";
                var_dump($ab_lastTripPeriodScheduled);
                echo "<hr>";
            }
        }
        ?>
    </body>
</html>
