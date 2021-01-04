<?php
require_once 'Controller/RouteXLController.php';
$routeController = new RouteXLController();
$routes = $routeController->getRoutes();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>

            table, th, td {
                border: 1px solid black;
                border-collapse: collapse
            }

            .navbar {
                overflow: hidden;
                background-color: lightgreen;
                position: fixed;
                top: 0;
                width: 100%;
                height: 35px;
            }
            form{
                padding: 8px 15px;
            }


            .navbar a {
                float: left;
                display: block;
                color: #f2f2f2;
                text-align: center ;
                color: black;
                padding: 6px 15px;
                text-decoration: none;
                font-size: 17px;
            }

            .navbar a:hover {
                background: #ddd;
                color: black;
            }

        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
            <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a>
            <a href="intervals.php">ინტერვალები</a>
            <a href="concurrentlyHaltedBuses.php">ერთდროულად მდგომი ავტობუსები</a>


            <form action='excelExport.php' method='POST'><input type='hidden' name='export' value='export'><input type='submit' value='ექსელში ექსპორტი' ></form>

        </div>

        <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
        <?php
        foreach ($routes as $route) {
            echo "<hr>მარშრუტი N: " . $route->getNumber() . "<br>";
            $days = $route->getDays();

            foreach ($days as $day) {

                echo $day->getDateStamp() . "<hr>";

                $exoduses = $day->getExoduses();

                foreach ($exoduses as $exodus) {
                    $tableConstructor = "<table style='width:400px'><tbody>";

                    $tableConstructor .= "<tr>"
                            . "<td colspan='13' style=' text-align: center; '><label><b>გასვლა N:" . $exodus->getNumber() . "</b></label></td>"
                            . "</tr>";
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {



                        $tableConstructor .= "<tr>"
                                . "<th colspan='3'>გასვლის დრო</th>"
                                . "<th></th>"
                                . "<th colspan='3'>მისვლის დრო</th>"
                                . "<th style='background-color:black'></th>"
                                . "<th colspan='5'>გამოთვლები</th>"
                                . "</tr>"
                                . "<tr>"
                                . "<th>გეგმიუირი</th>"
                                . "<th>ფაქტიური</th>"
                                . "<th>სხვაობა</th>"
                                . "<th>------</th>"
                                . "<th>გეგმიუირი</th>"
                                . "<th>ფაქტიური</th>"
                                . "<th>სხვაობა</th>"
                                . "<th style='background-color:black'>-</th>"
                                . "<th>ბრუნის(წირის) გეგმიური დრო</th>"
                                . "<th>ბრუნის(წირის) ფაქტიური დრო</th>"
                                . "<th>დგომის გეგმიური დრო</th>"
                                . "<th>დგომის ფაქტიური დრო</th>"
                                . "<th>'დაკარგული დრო'</th>"
                                . "</tr>";
                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {
                            $timeStamp = $tripPeriod->getLostTime();
                            $lightsForLostTime = $tripPeriod->getLightsForTimeStamp($timeStamp);
                            $tripPeriodType = $tripPeriod->getType();
                            $rowColor = "white";
                            if ($tripPeriodType == "break") {
                                $rowColor = "LightGray";
                            }
                            $tableConstructor .= "<tr style='background-color:$rowColor'>"
                                    . "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                    . "<td>" . $tripPeriod->getStartTimeActual() . "</td>"
                                    . "<td>" . $tripPeriod->getStartTimeDifference() . "</td>"
                                    . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                    . "<td style='background-color:black'></td>"
                                    . "<td>" . $tripPeriod->getScheduledTripPeriodTime() . "</td>"
                                    . "<td>" . $tripPeriod->getActualTripPeriodTime() . "</td>"
                                    . "<td>" . $tripPeriod->getScheduledHaltTime() . "</td>"
                                    . "<td>" . $tripPeriod->getActualHaltTime() . "</td>"
                                    . "<td style='background-color:$lightsForLostTime'>" . $tripPeriod->getLostTime() . "</td>"
                                    . "</tr>";
                        }
                    }
                    $tableConstructor .= "</tbody></table>";
                    echo $tableConstructor;

                    echo "<br>";
                }
            }
        }
        ?>

    </body>
</html>
