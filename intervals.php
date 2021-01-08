<!DOCTYPE html>
<?php
require_once 'Controller/RouteXLController.php';
$routeController = new RouteXLController();
$routes = $routeController->getRoutes();
?>
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
            <a href="index.php">მთავარ გვერძე დაბრნუნება</a>
            <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a>
            <a href="trips.php">ბრუნები</a>
            <a href="concurrentlyHaltedBuses.php">ერთდროულად მდგომი ავტობუსები</a>

        </div>
        <?php
        echo "intervals here";
        echo"<br><hr>";
        foreach ($routes as $route) {
            echo "მარშრუტი  # " . $route->getNumber();
            echo "<hr>";
            $days = $route->getDays();
            foreach ($days as $day) {
                $dateStamp = $day->getDateStamp();
                $dayIntervals = $day->getIntervals();

                $scheduledIntervals = $dayIntervals["scheduledIntervals"];
                $gpsIntervals = $dayIntervals["gpsIntervals"];
                echo $dateStamp;
                echo "<br>";
                $aTableConstructor = "<table name='aTable'><thead><tr><th colspan='5'>საგზურზე დაყრდნობით გამოთვლები</th></tr></tr><th>გასვლის<br>#</th><th>დაგეგმილი<br>გასვლის დრო</th><th>ფაქტიური<br>გასვლის დრო</th><th>დაგეგმილი<br>ინტერვალი</th><th>ფაქტიური<br>ინტერვალი</th><tr></thead>";
                $bTableConstructor = "<table name='bTable'><thead><tr><th colspan='5'>საგზურზე დაყრდნობით გამოთვლები</th></tr></tr><th>გასვლის<br>#</th><th>დაგეგმილი<br>გასვლის დრო</th><th>ფაქტიური<br>გასვლის დრო</th><th>დაგეგმილი<br>ინტერვალი</th><th>ფაქტიური<br>ინტერვალი</th><tr></thead>";


                foreach ($scheduledIntervals as $tripPeriods) {
                    foreach ($tripPeriods as $tripPeriod) {
                        $scheduledIntervalColor = $tripPeriod->getScheduledIntervalColor();
                        if ($tripPeriod->getType() == "ab") {
                            $aTableConstructor .= "<tr>";
                            $aTableConstructor .= "<td><b>" . $tripPeriod->getTripPeriodDNA()->getExodusNumber() . "</b></td>";
                            $aTableConstructor .= "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>";
                            $aTableConstructor .= "<td>" . $tripPeriod->getStartTimeActual() . "</td>";
                            $aTableConstructor .= "<td style='background-color:$scheduledIntervalColor'>" . $tripPeriod->getScheduledIntervalAfterPreviousBus() . "</td>";
                            $aTableConstructor .= "<td>" . $tripPeriod->getActualIntervalAfterPreviousBus() . "</td>";
                            $aTableConstructor .= "</tr>";
                        }
                        if ($tripPeriod->getType() == "ba") {
                            $bTableConstructor .= "<tr>";
                            $bTableConstructor .= "<td><b>" . $tripPeriod->getTripPeriodDNA()->getExodusNumber() . "</b></td>";
                            $bTableConstructor .= "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>";
                            $bTableConstructor .= "<td>" . $tripPeriod->getStartTimeActual() . "</td>";
                            $bTableConstructor .= "<td style='background-color:$scheduledIntervalColor'>" . $tripPeriod->getScheduledIntervalAfterPreviousBus() . "</td>";
                            $bTableConstructor .= "<td>" . $tripPeriod->getActualIntervalAfterPreviousBus() . "</td>";

                            $bTableConstructor .= "</tr>";
                        }
                    }
                }
                $aTableConstructor .= "</table>";
                $bTableConstructor .= "</table>";


                //gps Tables
                $a_gpsTableConstructor = "<table><thead><tr><th colspan='2'>GPS გამოთვლები</th></tr><tr><th>გასვლის<br>#</th><th>GPS<br>ინტერვალი</th></tr></thead>";
                $b_gpsTableConstructor = "<table><thead><tr><th colspan='2'>GPS გამოთვლები</th></tr><tr><th>გასვლის<br>#</th><th>GPS<br>ინტერვალი</th></tr></thead>";
                foreach ($gpsIntervals as $tripPeriods) {
                    foreach ($tripPeriods as $tripPeriod) {
                        if ($tripPeriod->getType() == "ab") {
                            $a_gpsTableConstructor .= "<tr>";
                            $a_gpsTableConstructor .= "<td><b>" . $tripPeriod->getTripPeriodDNA()->getExodusNumber() . "</b></td>";
                            $a_gpsTableConstructor .= "<td>" . $tripPeriod->getActualIntervalAfterPreviousBus() . "</td>";
                            $a_gpsTableConstructor .= "</tr>";
                        }
                        if ($tripPeriod->getType() == "ba") {
                            $b_gpsTableConstructor .= "<tr>";
                            $b_gpsTableConstructor .= "<td><b>" . $tripPeriod->getTripPeriodDNA()->getExodusNumber() . "</b></td>";
                            $b_gpsTableConstructor .= "<td>" . $tripPeriod->getActualIntervalAfterPreviousBus() . "</td>";
                            $b_gpsTableConstructor .= "</tr>";
                        }
                    }
                }
                $a_gpsTableConstructor .= "</table>";
                $b_gpsTableConstructor .= "</table>";


                $bigTableConstructor = "<table>"
                        . "<thead>"
                        . "<th colspan='2'>A_B</th>"
                        . "<th colspan='2'>B_A</th>"
                        . "</thead>";
                $bigTableConstructor .= "<tr>";
                $bigTableConstructor .= "<td style='vertical-align:top'>" . $aTableConstructor . "</td>";
                $bigTableConstructor .= "<td style='vertical-align:top'>" . $a_gpsTableConstructor . "</td>";
                $bigTableConstructor .= "<td style='vertical-align:top'>" . $bTableConstructor . "</td>";
                $bigTableConstructor .= "<td style='vertical-align:top'>" . $b_gpsTableConstructor . "</td>";
                $bigTableConstructor .= "</tr>";
                $bigTableConstructor .= "</table>";
                echo $bigTableConstructor;
                echo "---------------------------END OF DAY-----------------------------<hr>";
            }
            echo "<hr>++++++++++++++++END OF ROUTE++++++++++++++<hr>";
        }
        ?>
    </body>
</html>
