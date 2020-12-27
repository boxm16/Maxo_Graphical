<?php

require_once 'Controller/RouteXLController.php';
require_once 'Model/RouteXL.php';
if (!empty($_POST) && $_POST["export"] == "export") {
    $routeController = new RouteXLController();
    $routes = $routeController->getRoutes();
    $excelRows = rewriteRoutesToExcelRows($routes);

    exportProductDatabase($excelRows);
} else {
    echo "aq rogor moxvdi ????";
}

function rewriteRoutesToExcelRows($routes) {
    $bigArray = array();
    foreach ($routes as $route) {
        $routeNumber = $route->getNumber();
        $smallArray = array("", "", "", "MARSHRUTI N: " . $routeNumber, "", "", "");
        array_push($bigArray, $smallArray);
        $days = $route->getDays();
        foreach ($days as $day) {
            $dateStamp = $day->getDateStamp();
            $smallArray = array("", "", "", "TARIGI " . $dateStamp, "", "", "");
            array_push($bigArray, $smallArray);
            $exoduses = $day->getExoduses();
            foreach ($exoduses as $exodus) {
                $exodusNumber = $exodus->getNumber();
                $smallArray = array("", "", "", "GASVLA " . $exodusNumber, "", "", "");
                array_push($bigArray, $smallArray);
                $tripVouchers = $exodus->getTripVouchers();
                foreach ($tripVouchers as $tripVoucher) {
                    $tripVoucherNumber = $tripVoucher->getNumber();
                    $smallArray = array("", "", "", "SAGZURI N:  " . $tripVoucherNumber, "", "", "");
                    array_push($bigArray, $smallArray);
                    $tripPeriods = $tripVoucher->getTripPeriods();
                    foreach ($tripPeriods as $tripPeriod) {
                        $smallArray = array($tripPeriod->getStartTimeScheduled(), $tripPeriod->getStartTimeActual(), $tripPeriod->getStartTimeDifference(), $tripPeriod->getTypeGe(), $tripPeriod->getArrivalTimeScheduled(), $tripPeriod->getArrivalTimeActual(), $tripPeriod->getArrivalTimeDifference());
                        array_push($bigArray, $smallArray);
                    }
                }
            }
        }
    }

    return $bigArray;
}

function exportProductDatabase($rows) {
    $timestamp = time();
    $filename = 'Export_excel_' . $timestamp . '.xls';

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $isPrintHeader = false;
    foreach ($rows as $row) {
        if (!$isPrintHeader) {
            echo implode("\t", array_keys($row)) . "\n";
            $isPrintHeader = true;
        }
        echo implode("\t", array_values($row)) . "\n";
    }
    exit();
}
