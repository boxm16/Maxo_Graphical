<?php
require_once 'Controller/RouteXLController.php';
if (!isset($GLOBASL["routes"])) {
    $routeController = new RouteXLController();
} else {
    $routes = $GLOBASL["routes"];
}
//starting building tables
$tableBodyBuilder = "";
$size = count($routes);
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ინტერვალები</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
            /* navbar styling */
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background-color: green;
                position: fixed;
                top: 0;
                width: 2500px;
                z-index: 3;
            }

            li {
                float: left;
            }

            li a {
                display: block;
                color: white;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
            }

            li a:hover {
                background-color:white;
            }

            .active {
                background-color: lightgreen;
            }
            /* end of navbar styling */

            /* loader styling */
            .content {display:none;}
            .preload { width:100px;
                       height: 100px;
                       position: fixed;
                       top: 50%;
                       left: 50%;}
            /* end of loader styling*/


            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }


            /* for stickign */

            /* Standard Tables */

            table {

                border-collapse: collapse;
                border: 0.1em solid #d6d6d6;
            }

            th {
                vertical-align: bottom;
                background-color: #666;
                color: #fff;
            }


            /* Fixed Headers */

            th {
                position: -webkit-sticky;
                position: sticky;
                top: 45px;
                z-index: 2;

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
        <?php
        include 'navBar.php';
        ?>
        <div class="preload1"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content1">



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
                        $routeNumber = $route->getNumber();
                        $days = $route->getDays();
                        echo "<tr><td colspan=\"14\"  style=\"text-align: center; background-color:blue; color:white\">მარშრუტი # $routeNumber</td></tr>";
                        foreach ($days as $day) {
                            $dateStamp = $day->getDateStamp();
                            echo "<tr><td colspan=\"14\"  style=\"text-align: center; background-color:lightblue;\">$dateStamp</td></tr>";

                            //first voutcher time table --------
                            $directionsArray = $day->getVoucherScheduledTimeTableTripPeriods();
                            $abVoucherTableBuilder = "";
                            $baVoucherTableBuilder = "";
                            foreach ($directionsArray as $tripPeriods) {


                                foreach ($tripPeriods as $tripPeriod) {
                                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                    $startTimeActual = $tripPeriod->getStartTimeActual();
                                    $scheduledInterval = $tripPeriod->getScheduledInterval();
                                    $scheduledIntervaColor = $tripPeriod->getScheduledIntervalColor();
                                    $actualInterval = $tripPeriod->getActualInterval();
                                    $actualIntervalColor = $tripPeriod->getActualIntervalColor();
                                    $t=$tripPeriod->getTripPeriodDNA()->getExodusNumber();

                                    $row = "<tr>"
                                            . "<td>$startTimeScheduled</td>"
                                            . "<td>$startTimeActual</td>"
                                            . "<td style=\"background-color:$scheduledIntervaColor\">$scheduledInterval</td>"
                                            . "<td style=\"background-color:$actualIntervalColor\">$actualInterval</td>"
                                            . "<td> $t</td>"
                                            . "</tr>";

                                    if ($tripPeriod->getType() == "ab") {
                                        $abVoucherTableBuilder .= $row;
                                    }
                                    if ($tripPeriod->getType() == "ba") {
                                        $baVoucherTableBuilder .= $row;
                                    }
                                }
                            }
                            //end of voutcher time table --------
                            //now GPS time table --------
                            $gpsDirectionsArray = $day->getGPSTimeTableTripPeriods();
                            $ab_GpsTableBuilder = "";
                            $ba_GpsTableBuilder = "";
                            foreach ($gpsDirectionsArray as $tripPeriods) {


                                foreach ($tripPeriods as $tripPeriod) {
                                    if ($tripPeriod->getType() == "ab") {
                                        $sts = $tripPeriod->getStartTimeScheduled();
                                        $sta = $tripPeriod->getStartTimeActual();
                                        $stt = $tripPeriod->getType();
                                        $ab_GpsTableBuilder .= "<tr>"
                                                . "<td>$sts</td>"
                                                . "<td>$sta</td>"
                                                . "</tr>";
                                    }
                                    if ($tripPeriod->getType() == "ba") {
                                        $sts = $tripPeriod->getStartTimeScheduled();
                                        $sta = $tripPeriod->getStartTimeActual();
                                        $stt = $tripPeriod->getType();
                                        $ba_GpsTableBuilder .= "<tr>"
                                                . "<td>$sts</td>"
                                                . "<td>$sta</td>"
                                                . "</tr>";
                                    }
                                }
                            }
                            //end of GPS time table --------

                            $voucher_header = ""
                                    . "<tr><th colspan = \"5\" style=\"text-align: center\">საგზურზე დაყრდნობით გამოთვლები</th></tr>"
                                    . "<tr>"
                                    . "<th>გეგმიუირი<br>გასვლის<br>დრო</th>"
                                    . "<th>ფაქტიური<br>გასვლის<br>დრო</th>"
                                    . "<th>გეგმიუირი<br>ინტერვალი</th>"
                                    . "<th>ფაქტიური<br>ინტერვალი</th>"
                                    . "<th>.<br>გასვლის<br>#</th>"
                                    . "</tr>"
                                    . "";


                            $gps_header = ""
                                    . "<tr><th colspan=\"2\"  style=\"text-align: center\">GPS გამოთვლები</th></tr>"
                                    . "<tr>"
                                    . "<th>.<br>გასვლის<br>#</th>"
                                    . "<th>GPS<br>ინტერვალი</th>"
                                    . "</tr>";





                            echo "<tr>"
                            . "<td colspan=\"5\" style=\" vertical-align: top;\"><table style=\"width:100%\"><thead>$voucher_header</thead><tbody>$abVoucherTableBuilder</tbody></table></td><td colspan=\"2\"  style=\" vertical-align: top;\"><table style=\"width:100%\"><thead>$gps_header</thead><tbody>$ab_GpsTableBuilder</tbody></table></td>"
                            . ""
                            . "<td colspan=\"5\" style=\" vertical-align: top;\"><table style=\"width:100%\"><thead>$voucher_header</thead><tbody>$baVoucherTableBuilder</tbody></table></td><td colspan=\"2\"  style=\" vertical-align: top;\"><table style=\"width:100%\"><thead>$gps_header</thead><tbody>$ba_GpsTableBuilder</tbody></table></td>"
                            . "</tr>";
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        //this founction is for loader spinner. alsow first scrip srs is for this spinner, whout older does not work
        $(function () {
            $(".preload").fadeOut(2000, function () {
                $(".content").fadeIn(1000);
            });
        });
        //this code is for adding row clicking listener
        var chosenRow = null
        var cells = document.querySelectorAll("tr");

        for (var cell of cells) {
            cell.addEventListener('click', marker)
        }

        function marker(event) {
            var row = event.target.parentNode;
            if (chosenRow != null) {
                chosenRow.style.fontWeight = "normal";
            }
            row.style.fontWeight = "bold";
            chosenRow = row;
        }

    </script>
</body>
</html>
