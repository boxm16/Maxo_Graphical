<?php

require_once 'Controller/RouteXLController.php';
require_once 'mPDO.php';
//require_once 'clientId.php';
//--------------------------------------------------
$clientId = "0";
//-----------------------------------------------
/*
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
 */
$s = microtime(true);
$routeController = new RouteXLController();
//$routes = $routeController->getSiftedRoutes($clientId, $requestedRoutesAndDates);
$routes = $routeController->getFullRoutes($clientId);
$tripVouchersData = array();
$tripPeriodsData = array();
foreach ($routes as $route) {
    $days = $route->getDays();
    $routeNumber = $route->getNumber();
    foreach ($days as $day) {
        $dateStamp = $day->getDateStamp();
        $exoduses = $day->getExoduses();
        foreach ($exoduses as $exodus) {
            $exodusNumber = $exodus->getNumber();
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripPeriods = $tripVoucher->getTripPeriods();
                $tripVoucherNumber = $tripVoucher->getNumber();
                $busNumber = $tripVoucher->getBusNumber();
                $busType = $tripVoucher->getBusType();
                $driverNumber = $tripVoucher->getDriverNumber();
                $driverName = $tripVoucher->getDriverName();
                $notes = $tripVoucher->getNotes();

                $tv = array($tripVoucherNumber, $busNumber, $driverNumber, $driverName, $notes, $exodusNumber, $dateStamp, $routeNumber);

                foreach ($tripPeriods as $tripPeriod) {
                    $type = $tripPeriod->getType();
                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                    $startTimeActual = $tripPeriod->getStartTimeActual();
                    $startTimeDifference = $tripPeriod->getStartTimeDifference();
                    $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                    $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                    $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();
                    $tp = array($routeNumber, $dateStamp, $exodusNumber, $tripVoucherNumber, $driverNumber, $driverName, $busNumber, $busType, $startTimeScheduled, $startTimeScheduled, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference, $type, $notes);
                    array_push($tripPeriodsData, $tp);
                }
            }
        }
    }
}

$chunkedArray = array_chunk($tripPeriodsData, 4000);




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

    $index = 0;
    foreach ($chunkedArray as $data) {
        if ($index >= 2) {
            break;
        }
        $stmt = $pdo->multiPrepare('INSERT INTO join_table (route_number, date_stamp, exodus_number, trip_voucher_number, driver_number, driver_name, bus_number, bus_type, start_time_scheduled, start_time_actual, start_time_difference, arrival_time_scheduled, arrival_time_actual, arrival_time_difference, type, notes)', $data);
        $stmt->multiExecute($data);
        $index++;
    }

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