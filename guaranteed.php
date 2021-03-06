<?php
require_once 'Controller/RouteXLController.php';
require_once 'clientId.php';
require_once 'Controller/TimeCalculator.php';
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
$timeCalculator = new TimeCalculator()
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
        <style>
            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
        </style>

    </head>
    <body>
    <center> 
        <h1>საგარანტიო რეისები</h1>
        <form action="excelExportDispatcher.php" method="POST">
            <input hidden name="guaranteed">
            <input type='submit' value="ექსელში ექსპორტი" style="background-color: orange;  font-size: 20px;">
        </form>
    </center>
    <h4>1-დაგეგმილი ბოლო რეისის გეგმიური გასვლის დრო. 2-დაგეგმილი ბოლო რეისის ფაქტიური გასვლის დრო.   3- დაგეგმილი ბოლო რეისის გასვლის ნომერი.   <br>
        4-GPS მაჩვენებლებით გამოთვლილი ბოლო რეისის გასვლის ნომერი. 5-GPS მაჩვენებლებით გამოთვლილი ბოლო რეისის გეგმიური გასვლის დრო. 6-GPS მაჩვენებლებით გამოთვლილი ბოლო რეისის ფაქტიური გასვლის დრო<br>
        რ.ვ.ი. - რეისი ვერ იძებნება </h4>
    <table>
        <thead>
            <tr>
                <th>-</th>
                <th>-</th>
                <th colspan="7">A_B მიმართულება</th>
                <th>-</th>
                <th colspan="7">B_A მიმართულება</th>
            </tr>
            <tr>
                <th>
                    მარშ. #
                </th>
                <th>
                    თარიღი
                </th>
                <th>
                    მძღოლი
                </th>
                <th><b>1*</b><br>
                    A_B     გეგმიური გასვლის დრო
                </th>
                <th><b>2*</b><br>
                    A_B     ფაქტიური გასვლის დრო
                </th>
                <th><b>3*</b><br>გასვლის ნომერი</th>
                <th><b>4*</b><br>GPS გასვლის ნომერი</th>
                <th><b>5*</b><br>
                    A_B    GPS გეგმიური გასვლის დრო
                </th>
                <th><b>6*</b><br>
                    A_B    ფაქტიური გასვლის დრო
                </th>
                <th>
                    -
                </th>
                <th>
                    მძღოლი
                </th>
                <th><b>1*</b><br>
                    B_A     გეგმიური გასვლის დრო
                </th>
                <th><b>2*</b><br>
                    B_A     ფაქტიური გასვლის დრო
                </th>
                <th><b>3*</b><br>გასვლის ნომერი</th>
                <th><b>4*</b><br>GPS გასვლის ნომერი</th>
                <th><b>5*</b><br>
                    B_A    GPS გეგმიური გასვლის დრო
                </th>
                <th><b>6*</b><br>
                    B_A    GPS   ფაქტიური გასვლის დრო
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $bodyBuilder = "";
            foreach ($routes as $route) {
                $routeNumber = $route->getNumber();
                $days = $route->getDays();
                foreach ($days as $day) {
                    $dateStamp = $day->getDateStamp();
                    $lastTrips = $day->getLastTrips();
                    $ab_lastTripPeriodScheduled = $lastTrips["ab_lastTripPeriodScheduled"];
                    $ba_lastTripPeriodScheduled = $lastTrips["ba_lastTripPeriodScheduled"];
                    $ab_lastTripPeriodActual = $lastTrips["ab_lastTripPeriodActual"];
                    $ba_lastTripPeriodActual = $lastTrips["ba_lastTripPeriodActual"];

                    $ab_light = "white";
                    $ba_light = "white";
                    if ($ab_lastTripPeriodScheduled != null) {
                        $ab_lastTripPeriodStartTimeScheduled = $ab_lastTripPeriodScheduled->getStartTimeScheduled();
                        $ab_lastTripPeriodStartTimeActual = $ab_lastTripPeriodScheduled->getStartTimeActual();
                        $ab_lastTripPeriodExodusNumber = $ab_lastTripPeriodScheduled->getTripPeriodDNA()->getExodusNumber();
                        $ab_lastTripPeriodType = $ab_lastTripPeriodScheduled->getType();
                        $ab_driverName = $ab_lastTripPeriodScheduled->getTripPeriodDNA()->getDriverName();
                    } else {
                        $ab_lastTripPeriodStartTimeScheduled = "რ.ვ.ი.*";
                        $ab_lastTripPeriodStartTimeActual = "რ.ვ.ი.*";
                        $ab_lastTripPeriodExodusNumber = "რ.ვ.ი.*ა";
                        $ab_lastTripPeriodType = "რ.ვ.ი.*";
                    }
                    if ($ba_lastTripPeriodScheduled != null) {
                        $ba_lastTripPeriodStartTimeScheduled = $ba_lastTripPeriodScheduled->getStartTimeScheduled();
                        $ba_lastTripPeriodStartTimeActual = $ba_lastTripPeriodScheduled->getStartTimeActual();
                        $ba_lastTripPeriodExodusNumber = $ba_lastTripPeriodScheduled->getTripPeriodDNA()->getExodusNumber();
                        $ba_lastTripPeriodType = $ba_lastTripPeriodScheduled->getType();
                        $ba_driverName = $ba_lastTripPeriodScheduled->getTripPeriodDNA()->getDriverName();
                    } else {
                        $ba_lastTripPeriodStartTimeScheduled = "რ.ვ.ი.*";
                        $ba_lastTripPeriodStartTimeActual = "რ.ვ.ი.*";
                        $ba_lastTripPeriodExodusNumber = "რ.ვ.ი.*";
                        $ba_lastTripPeriodType = "რ.ვ.ი.*";
                    }
                    if ($ab_lastTripPeriodActual != null) {
                        $gps_ab_lastTripScheduled = $ab_lastTripPeriodActual->getStartTimeScheduled();
                        $gps_ab_lastTripActual = $ab_lastTripPeriodActual->getStartTimeActual();
                        $gps_ab_lastTripExodusNumber = $ab_lastTripPeriodActual->getTripPeriodDNA()->getExodusNumber();
                        $gps_ab_lastTripPeriodType = $ab_lastTripPeriodActual->getType();
                    } else {
                        $gps_ab_lastTripScheduled = "რ.ვ.ი.*";
                        $gps_ab_lastTripActual = "რ.ვ.ი.*";
                        $gps_ab_lastTripExodusNumber = "რ.ვ.ი.*";
                        $gps_ab_lastTripPeriodType = "რ.ვ.ი.*";
                    }

                    if ($ba_lastTripPeriodActual != null) {
                        $gps_ba_lastTripScheduled = $ba_lastTripPeriodActual->getStartTimeScheduled();
                        $gps_ba_lastTripActual = $ba_lastTripPeriodActual->getStartTimeActual();
                        $gps_ba_lastTripExodusNumber = $ba_lastTripPeriodActual->getTripPeriodDNA()->getExodusNumber();
                        $gps_ba_lastTripPeriodType = $ba_lastTripPeriodActual->getType();
                    } else {
                        $gps_ba_lastTripScheduled = "რ.ვ.ი.*";
                        $gps_ba_lastTripActual = "რ.ვ.ი.*";
                        $gps_ba_lastTripExodusNumber = "რ.ვ.ი.*";
                        $gps_ba_lastTripPeriodType = "რ.ვ.ი.*";
                    }
//HERE START CHECKING ALGORITHM
                    if ($ab_lastTripPeriodScheduled != null && $ab_lastTripPeriodActual != null) {
                        if ($ab_lastTripPeriodStartTimeActual != "") {
                            $ab_lastTripPeriodStartTimeScheduledInSeconds = $timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeScheduled);
                            $ab_lastTripPeriodStartTimeActualInSeconds = $timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeActual);
                            if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $ab_lastTripPeriodStartTimeActualInSeconds) > 60) {
                                $ab_light = "yellow";
                                if ($gps_ab_lastTripActual != "") {
                                    $gps_ab_lastTripActualInSeconds = $timeCalculator->getSecondsFromTimeStamp($gps_ab_lastTripActual);
                                    if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $gps_ab_lastTripActualInSeconds) > 60) {
                                        $ab_light = "red";
                                    }
                                }
                            }
                        } else {
                            $ab_lastTripPeriodStartTimeScheduledInSeconds = $timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeScheduled);
                            $gps_ab_lastTripActualInSeconds = $timeCalculator->getSecondsFromTimeStamp($gps_ab_lastTripActual);

                            if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $gps_ab_lastTripActualInSeconds) > 60) {
                                $ab_light = "red";
                            }
                        }
                    }

                    if ($ba_lastTripPeriodScheduled != null && $ba_lastTripPeriodActual != null) {
                        if ($ba_lastTripPeriodStartTimeActual != "") {
                            $ba_lastTripPeriodStartTimeScheduledInSeconds = $timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeScheduled);
                            $ba_lastTripPeriodStartTimeActualInSeconds = $timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeActual);
                            if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $ba_lastTripPeriodStartTimeActualInSeconds) > 60) {
                                $ba_light = "yellow";
                                if ($gps_ba_lastTripActual != "") {
                                    $gps_ba_lastTripActualInSeconds = $timeCalculator->getSecondsFromTimeStamp($gps_ba_lastTripActual);
                                    if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $gps_ba_lastTripActualInSeconds) > 60) {
                                        $ba_light = "red";
                                    }
                                }
                            }
                        } else {
                            $ba_lastTripPeriodStartTimeScheduledInSeconds = $timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeScheduled);
                            $gps_ba_lastTripActualInSeconds = $timeCalculator->getSecondsFromTimeStamp($gps_ba_lastTripActual);

                            if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $gps_ba_lastTripActualInSeconds) > 60) {
                                $ba_light = "red";
                            }
                        }
                    }


                    $row = "<tr>"
                            . "<td>$routeNumber</td>"
                            . "<td>$dateStamp</td>"
                            . "<td>$ab_driverName</td>"
                            . "<td>$ab_lastTripPeriodStartTimeScheduled</td>"
                            . "<td style=\"background-color:$ab_light\">$ab_lastTripPeriodStartTimeActual</td>"
                            . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$ab_lastTripPeriodType&startTimeScheduled=$ab_lastTripPeriodStartTimeScheduled'  target='_blank'>$ab_lastTripPeriodExodusNumber</a></td>"
                            . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$gps_ab_lastTripPeriodType&startTimeScheduled=$gps_ab_lastTripScheduled'  target='_blank'>$gps_ab_lastTripExodusNumber</a></td>"
                            . "<td>$gps_ab_lastTripScheduled</td>"
                            . "<td>$gps_ab_lastTripActual</td>"
                            . "<td>-</td>"
                            . "<td>$ba_driverName</td>"
                            . "<td>$ba_lastTripPeriodStartTimeScheduled</td>"
                            . "<td style=\"background-color:$ba_light\">$ba_lastTripPeriodStartTimeActual</td>"
                            . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$ba_lastTripPeriodType&startTimeScheduled=$ba_lastTripPeriodStartTimeScheduled'  target='_blank'>$ba_lastTripPeriodExodusNumber</a></td>"
                            . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$gps_ba_lastTripPeriodType&startTimeScheduled=$gps_ba_lastTripScheduled'  target='_blank'>$gps_ba_lastTripExodusNumber</a></td>"
                            . "<td>$gps_ba_lastTripScheduled</td>"
                            . "<td>$gps_ba_lastTripActual</td>"
                            . "</tr>";

                    echo $row;
                }
            }
            echo $bodyBuilder;
            ?>

        </tbody>
    </table>



    <hr>
    <br>
    <br><br>
    <hr>
</body>
</html>
