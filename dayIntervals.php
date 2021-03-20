<?php
require_once 'Controller/RouteDBController.php';
if (isset($_GET["routeNumber"]) && isset($_GET["dateStamp"]) && isset($_GET["tripPeriodType"]) && isset($_GET["startTimeScheduled"])) {
    $routeNumber = $_GET["routeNumber"];
    $dateStamp = $_GET["dateStamp"];
    //convert dataStamp
    $time = strtotime(str_replace('/', '-', $dateStamp));
    $dateStamp = date('Y-m-d', $time);

    $tripPeriodTypeFomRequest = $_GET["tripPeriodType"];
    $startTimeScheduledFomRequest = $_GET["startTimeScheduled"];
    $dayIntervalsDetails = "$dateStamp,  მარშრუტი # $routeNumber";

    $routeController = new RouteDBController();
    $routes = $routeController->getRouteForDay($routeNumber, $dateStamp); //actually return one route, but i just use already created functions, so, dont worry
} else {
    $dayIntervalsDetails = "რაღაც შეცდომა მოხდა, სცადე თავიდან";
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>

            /* for stickign */

            /* Standard Tables */

            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }


            th {
                vertical-align: bottom;
                background-color: white;
                color: black;
            }


            /* Fixed Headers */

            th {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                z-index: 2;
                background-color: #666;
                color: #fff;

            }

            th[scope=row] {
                position: -webkit-sticky;
                position: sticky;
                left: 0;
                z-index: 1;
            }

            th[scope=row] {
                vertical-align: top;
                color: inherit;
                background-color: inherit;
                background: linear-gradient(90deg, transparent 0%, transparent calc(100% - .05em), #d6d6d6 calc(100% - .05em), #d6d6d6 100%);
            }


        </style>
    </head>
    <body>
        <div style="background-color:green;color:white"> <center><h2> <?php echo $dayIntervalsDetails; ?> </h2></center></div>
        <table style="width:100%">
            <thead>
                <tr>
                    <th colspan="7" style="text-align: center">A_B</th>
                    <th colspan="7" style="text-align: center">B_A</th>
                </tr>

            </thead>


            <tbody>
                <?php
                foreach ($routes as $route) {
                    $routeNumberFromData = $route->getNumber();
                    if ($routeNumber == $routeNumberFromData) {
                        $days = $route->getDays();
                        echo "<tr><td colspan=\"14\"  style=\"text-align: center; background-color:blue; color:white\">მარშრუტი # $routeNumber</td></tr>";
                        foreach ($days as $day) {
                            $dateStampFromData = $day->getDateStamp();
                            if ($dateStamp == $dateStampFromData) {
                                echo "<tr><td colspan=\"14\"  style=\"text-align: center; background-color:lightblue;\">$dateStamp</td></tr>";

                                $voucher_header = ""
                                        . "<tr><th colspan = \"5\" style=\"text-align: center\">საგზურზე დაყრდნობით გამოთვლები</th></tr>"
                                        . "<tr>"
                                        . "<th>გეგმ.<br>გას.<br>დრო</th>"
                                        . "<th>ფაქტ.<br>გას.<br>დრო</th>"
                                        . "<th>გეგმ.<br>ინტ.</th>"
                                        . "<th>ფაქტ.<br>ინტ.</th>"
                                        . "<th>.<br>გას.<br>#</th>"
                                        . "</tr>"
                                        . "";


                                $gps_header = ""
                                        . "<tr><th colspan=\"5\"  style=\"text-align: center\">GPS გამოთვლები</th></tr>"
                                        . "<tr>"
                                        . "<th>.<br>გას.<br>#</th>"
                                        . "<th>გეგმ.<br>გას.<br>დრო</th>"
                                        . "<th>გეგმ.<br>ინტ.</th>"
                                        . "<th>ფაქტ.<br>გას.<br>დრო</th>"
                                        . "<th>GPS<br>ინტ.</th>"
                                        . "</tr>";


                                $intervals = $day->getIntervals();
                                $scheduledIntervals = $intervals["scheduledIntervals"];
                                $gpsIntervals = $intervals["gpsIntervals"];

                                $abVoucherTableBodyBuilder = "";
                                $baVoucherTableBodyBuilder = "";

//first voucher table body builder
                                foreach ($scheduledIntervals as $direction) {
                                    foreach ($direction as $tripPeriod) {
                                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                        $startTimeActual = $tripPeriod->getStartTimeActual();
                                        $scheduledInterval = $tripPeriod->getScheduledInterval();
                                        $scheduledIntervaColor = $tripPeriod->getScheduledIntervalColor();
                                        $actualInterval = $tripPeriod->getActualInterval();
                                        $actualIntervalColor = $tripPeriod->getActualIntervalColor();
                                        $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
                                        $rowColor = "white";
                                        if ($tripPeriodTypeFomRequest == $tripPeriod->getType() && $startTimeScheduledFomRequest == $tripPeriod->getStartTimeScheduled()) {
                                            $rowColor = "lightgreen";
                                        }
                                        $row = "<tr>"
                                                . "<td style=\"background-color:$rowColor\">$startTimeScheduled</td>"
                                                . "<td>$startTimeActual</td>"
                                                . "<td style=\"background-color:$scheduledIntervaColor\">$scheduledInterval</td>"
                                                . "<td style=\"background-color:$actualIntervalColor\">$actualInterval</td>"
                                                . "<td><b><a href='exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled'  target='_blank'>" . $exodusNumber . "</a></b></td>"
                                                . "</tr>";

                                        if ($tripPeriod->getType() == "ab") {
                                            $abVoucherTableBodyBuilder .= $row;
                                        }
                                        if ($tripPeriod->getType() == "ba") {
                                            $baVoucherTableBodyBuilder .= $row;
                                        }
                                    }
                                }

                                //now gps table body builder
                                $ab_GpsTableBuilder = "";
                                $ba_GpsTableBuilder = "";

                                foreach ($gpsIntervals as $direction) {
                                    foreach ($direction as $tripPeriod) {
                                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                        $startTimeActual = $tripPeriod->getStartTimeActual();

                                        $scheduledInterval = $tripPeriod->getScheduledInterval();
                                        $scheduledIntervaColor = $tripPeriod->getScheduledIntervalColor();

                                        $gpsBasedActualInterval = $tripPeriod->getGpsBasedActualInterval();
                                        $gpsBasedActualIntervalColor = $tripPeriod->getGpsBasedActualIntervalColor();
                                        $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
                                        $rowColor = "white";
                                        if ($tripPeriodTypeFomRequest == $tripPeriod->getType() && $startTimeScheduledFomRequest == $tripPeriod->getStartTimeScheduled()) {
                                            $rowColor = "lightgreen";
                                        }
                                        $row = "<tr>"
                                                . "<td><b><a href='exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled'  target='_blank'>" . $exodusNumber . "</a></b></td>"
                                                . "<td style=\"background-color:$rowColor\">$startTimeScheduled</td>"
                                                . "<td>$startTimeActual</td>"
                                                . "<td style=\"background-color:$scheduledIntervaColor\">$scheduledInterval</td>"
                                                . "<td style=\"background-color:$gpsBasedActualIntervalColor\">$gpsBasedActualInterval</td>"
                                                . "</tr>";

                                        if ($tripPeriod->getType() == "ab") {
                                            $ab_GpsTableBuilder .= $row;
                                        }
                                        if ($tripPeriod->getType() == "ba") {
                                            $ba_GpsTableBuilder .= $row;
                                        }
                                    }
                                }
                                echo "<tr>"
                                . "<td colspan=\"5\" style=\" vertical-align: top;\">"
                                . "<table style=\"width:100%\">"
                                . "<thead>$voucher_header</thead>"
                                . "<tbody>$abVoucherTableBodyBuilder</tbody>"
                                . "</table>"
                                . "</td>"
                                . "<td colspan=\"2\"  style=\" vertical-align: top;\">"
                                . "<table style=\"width:100%\">"
                                . "<thead>$gps_header</thead>"
                                . "<tbody>$ab_GpsTableBuilder</tbody>"
                                . "</table>"
                                . "</td>"
                                . "<td style=\"border: 2px solid green;\">.</td>"
                                . "<td colspan=\"5\" style=\" vertical-align: top;\">"
                                . "<table style=\"width:100%\">"
                                . "<thead>$voucher_header</thead>"
                                . "<tbody>$baVoucherTableBodyBuilder</tbody>"
                                . "</table>"
                                . "</td>"
                                . "<td colspan=\"2\"  style=\" vertical-align: top;\">"
                                . "<table style=\"width:100%\">"
                                . "<thead>$gps_header</thead>"
                                . "<tbody>$ba_GpsTableBuilder</tbody>"
                                . "</table>"
                                . "</td>"
                                . "</tr>";
                            }
                        }
                    }
                }
                ?>

            </tbody>
        </table>
    </body>
</html>
