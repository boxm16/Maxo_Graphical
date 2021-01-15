<?php
require_once 'Controller/RouteXLController.php';
if (!isset($GLOBASL["routes"])) {
    $routeController = new RouteXLController();
    $routes = $GLOBALS["routes"];
} else {
    $routes = $GLOBALS["routes"];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ბრუნები დეტალურად</title>
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


            /*table styling */
            table thead tr th {
                /* Important  for table head sticking whre it is*/
                background-color: white;
                position: sticky;
                z-index: 100;
                top: 50px;

            }
            /* other staff below */
            table, thead, tr, th, td {
                border: 2px solid black;

            }

        </style>
    </head>
    <body>
        <?php
        include 'navBar.php';
        ?>
        <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content">
            <table  id="header-fixed" style="width:100%">
                <thead>

                    <tr>
                        <th>გეგმიუირი<br>გასვლის<br>დრო</th>
                        <th>ფაქტიური<br>გასვლის<br>დრო</th>
                        <th>სხვაობა</th>
                        <th>------</th>
                        <th>გეგმიუირი<br>მისვლის<br>დრო</th>
                        <th>ფაქტიური<br>მისვლის<br>დრო</th>
                        <th>სხვაობა</th>
                        <th></th>
                        <th>წირის<br>გეგმიური<br>დრო</th>
                        <th>წირის<br>ფაქტიური<br>დრო</th>
                        <th>დგომის<br>გეგმიური<br> დრო</th>
                        <th>დგომის<br>ფაქტიური<br>დრო</th>
                        <th>'დაკარგული<br>დრო'</th>
                    </tr>
                </thead>
                <tbody>
                <tbody>
                    <?php
                    foreach ($routes as $route) {

                        $days = $route->getDays();
                        echo "<tr><td colspan='13'><center>მარშრუტა #: " . $route->getNumber() . "</center></td></tr>";


                        foreach ($days as $day) {
                            echo "<tr><td colspan='13'><center>თარიღი: " . $day->getDateStamp() . "</center></td></tr>";
                            $exoduses = $day->getExoduses();
                            foreach ($exoduses as $exodus) {
                                echo "<tr><td colspan='13'><center>გასვლა #: " . $exodus->getNumber() . "<center></td></tr>";

                                $tripVouchers = $exodus->getTripVouchers();
                                foreach ($tripVouchers as $tripVoucher) {
                                    echo "<tr><td colspan='13'><center>მარშრუტი #" . $route->getNumber()
                                    . ". თარიღი:" . $day->getDateStamp()
                                    . ". გასვლია #" . $exodus->getNumber()
                                    . ". საგზური #" . $tripVoucher->getNumber()
                                    // . " Bus Type: " . $tripVoucher->getBusType()
                                    //. " Bus Number: " . $tripVoucher->getBusNumber()
                                    //. "/// Driver Number: " . $tripVoucher->getDriverNumber()
                                    //. "/// Driver Name: " . $tripVoucher->getDriverName()
                                    . ". შენიშვნები: " . $tripVoucher->getNotes() . "</center></td></tr>";

                                    $tripPeriods = $tripVoucher->getTripPeriods();
                                    foreach ($tripPeriods as $tripPeriod) {
                                        $lostTimeLights = $tripPeriod->getLightsForLostTime();
                                        $rowColor = "white";
                                        if ($tripPeriod->getType() == "break") {
                                            $rowColor = "lightgrey";
                                        }
                                        echo "<tr style=\"background-color:$rowColor;\">"
                                        . "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getStartTimeActual() . "</td>"
                                        . "<td>" . $tripPeriod->getStartTimeDifference() . "</td>"
                                        . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                        . "<td></td>"
                                        . "<td>" . $tripPeriod->getTripPeriodScheduledTime() . "</td>"
                                        . "<td>" . $tripPeriod->getTripPeriodActualTime() . "</td>"
                                        . "<td>" . $tripPeriod->getHaltTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getHaltTimeActual() . "</td>"
                                        . "<td style='background-color:$lostTimeLights'>" . $tripPeriod->getLostTime() . "</td>"
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

        </script>
    </body>
</html>
