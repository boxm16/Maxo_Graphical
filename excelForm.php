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


$routeController = new RouteXLController();
$routes = $routeController->getSiftedRoutes($clientId, $requestedRoutesAndDates);
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ექსელის ფორმა</title>
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
                background-color: #D170F7;
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
        <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content"> 


            <table style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align: center">მარშრუტის #</th>
                        <th style="text-align: center">თარიღი</th>
                        <th style="text-align: center">ავტობუსის #</th>
                        <th style="text-align: center">გასვლის #</th>
                        <th style="text-align: center">მძღოლი</th>
                        <th style="text-align: center">მიმართულება</th>
                        <th style="text-align: center">გასვლის<br>გეგმიური<br>დრო</th>
                        <th style="text-align: center">გასვლის<br>ფაქტიური<br>დრო</th>
                        <th style="text-align: center">მისვლის<br>გეგმიური<br>დრო</th>
                        <th style="text-align: center">მისვლის<br>ფაქტიური<br>დრო</th>
                        <th style="text-align: center">წირის<br>გეგმიური<br>დრო</th>
                        <th style="text-align: center">წირის<br>ფაქტიური<br>დრო</th>
                        <th style="text-align: center">სხვაობა</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    foreach ($routes as $route) {
                        $days = $route->getDays();

                        foreach ($days as $day) {
                            $exoduses = $day->getExoduses();
                            foreach ($exoduses as $exodus) {
                                $tripVouchers = $exodus->getTripVouchers();
                                foreach ($tripVouchers as $tripVoucher) {
                                    $tripPeriods = $tripVoucher->getTripPeriods();
                                    $firstTripPeriodStartPoint = $tripVoucher->getFirstTripPeriodStartPoint();
                                    $lastTripPeriodEndPoint = $tripVoucher->getLastTripPeriodEndPoint();
                                    foreach ($tripPeriods as $tripPeriod) {
                                        $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
                                        $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
                                        $busNumber = $tripPeriod->getTripPeriodDNA()->getBusNumber();
                                        $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
                                        $driverName = $tripPeriod->getTripPeriodDNA()->getDriverName();
                                        $tripPeriodType = $tripPeriod->getTypeGe();
                                        if ($tripPeriodType == "ბაზიდან გასვლა") {
                                            $tripPeriodType .= "-" . $firstTripPeriodStartPoint;
                                        }
                                        if ($tripPeriodType == "ბაზაში დაბრუნება") {
                                            $tripPeriodType = "$lastTripPeriodEndPoint-ბაზაში დაბრუნება";
                                        }

                                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                        $startTimeActual = $tripPeriod->getStartTimeActual();
                                        $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                                        $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                                        $tripPeriodScheduledTime = $tripPeriod->getTripPeriodScheduledTime();
                                        $tripPeriodActualTime = $tripPeriod->getTripPeriodActualTime();
                                        $tripPeriodDifferenceTime = $tripPeriod->getTripPeriodDifferenceTime();
                                        $tripPeriodDifferenceTimeColor = $tripPeriod->getTripPeriodDifferenceTimeColor();
                                        echo "<tr> "
                                        . "<td>$routeNumber</td>"
                                        . "<td>$dateStamp</td>"
                                        . "<td>$busNumber</td>"
                                        . "<td>$exodusNumber</td>"
                                        . "<td>$driverName</td>"
                                        . "<td>$tripPeriodType</td>"
                                        . "<td>$startTimeScheduled</td>"
                                        . "<td>$startTimeActual</td>"
                                        . "<td>$arrivalTimeScheduled</td>"
                                        . "<td>$arrivalTimeActual</td>"
                                        . "<td>$tripPeriodScheduledTime</td>"
                                        . "<td>$tripPeriodActualTime</td>"
                                        . "<td style=\"width:100px;background-color:$tripPeriodDifferenceTimeColor\">$tripPeriodDifferenceTime</td>"
                                        . "</tr>";
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>

        </div>

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

            //this is for phpsperadsheet 
            $(document).ready(function () {
                $('#convert').click(function () {
                    var table_content = '<table>';
                    table_content += $('#mainTable').html();
                    table_content += '</table>';
                    $('#file_content').val(table_content);
                    $('#convert_form').submit();
                });
            });

        </script>
    </body>
</html>
