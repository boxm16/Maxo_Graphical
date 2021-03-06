<?php

require_once 'Controller/RouteXLController.php';
require_once 'mPDO.php';
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
$routes = $routeController->getSiftedRoutes($requestedRoutesAndDates);

$data = array();
foreach ($routes as $route) {
    $days = $route->getDays();
    foreach ($days as $day) {
        $exoduses = $day->getExoduses();
        foreach ($exoduses as $exodus) {
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripPeriods = $tripVoucher->getTripPeriods();

                foreach ($tripPeriods as $tripPeriod) {
                    $type = $tripPeriod->getType();
                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                    $startTimeActual = $tripPeriod->getStartTimeActual();
                    $startTimeDifference = $tripPeriod->getStartTimeDifference();
                    $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                    $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                    $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();
                    $tp = array($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
                    array_push($data, $tp);
                }
            }
        }
    }
}


$host = 'remotemysql.com';
$db = '9w706j5s1P';
$user = '9w706j5s1P';
$pass = 'zEcc9jTAyc';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new mPDO($dsn, $user, $pass, $options);

//one version
    $stmt = $pdo->multiPrepare('INSERT INTO trip_period (type, start_time_scheduled, start_time_actual, start_time_difference, arrival_time_scheduled, arrival_time_actual, arrival_time_difference)', $data);
    $stmt->multiExecute($data);
    /*
      //second version !!!!!NOT WORKING
      $pdo->beginTransaction(); //**** ADD THIS
      $stmt = $pdo->prepare('INSERT INTO trip_period  (type, start_time_scheduled, start_time_actual, start_time_difference, arrival_time_scheduled, arrival_time_actual, arrival_time_difference) VALUES (?,?,?,?,?,?,?)');
      for ($i = 0; $i < count($data); $i++) {
      $stmt->execute($data[$i]);
      }
      $pdo->commit(); //**** ADD THIS
     */

    $e = microtime(true);
    echo ($e - $s);
} catch (\PDOException $e) {
    echo $e->getMessage() . " Eroor Code:";
    echo $e->getCode();
}