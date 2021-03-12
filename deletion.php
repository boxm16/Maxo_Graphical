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
foreach ($routes as $key => $value) {
    echo $key . "<br>";
}
$e = microtime(true);
echo ($e - $s);
