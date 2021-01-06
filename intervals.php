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

                echo $dateStamp;
                echo "<br>";
                $aTableConstructor = "<table name='aTable'><thead><th>გასვლის<br>#</th><th>დაგეგმილი<br>გასვლის დრო</th><th>დაგეგმილი<br>ინტერვალი</th><th>ფაკტიური<br>ინტერვალი</th></thead>";
                $bTableConstructor = "<table name='bTable'><thead><th>გასვლის<br>#</th><th>დაგეგმილი<br>გასვლის დრო</th><th>დაგეგმილი<br>ინტერვალი</th><th>ფაკტიური<br>ინტერვალი</th></thead>";

                foreach ($dayIntervals as $scheduledIntervals) {



                    foreach ($scheduledIntervals as $tripPeriod) {
                        $scheduledIntervalColor = $tripPeriod->getScheduledIntervalColor();
                        if ($tripPeriod->getType() == "ab") {
                            $aTableConstructor .= "<tr>";
                            $aTableConstructor .= "<td><b>" . $tripPeriod->getTripPeriodDNA()->getExodusNumber() . "</b></td>";
                            $aTableConstructor .= "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>";
                            $aTableConstructor .= "<td style='background-color:$scheduledIntervalColor'>" . $tripPeriod->getScheduledIntervalAfterPreviousBus() . "</td>";
                            $aTableConstructor .= "<td>" . $tripPeriod->getActualIntervalAfterPreviousBus() . "</td>";
                            $aTableConstructor .= "</tr>";
                        }
                        if ($tripPeriod->getType() == "ba") {
                            $bTableConstructor .= "<tr>";
                            $bTableConstructor .= "<td><b>" . $tripPeriod->getTripPeriodDNA()->getExodusNumber() . "</b></td>";
                            $bTableConstructor .= "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>";
                            $bTableConstructor .= "<td style='background-color:$scheduledIntervalColor'>" . $tripPeriod->getScheduledIntervalAfterPreviousBus() . "</td>";
                            $bTableConstructor .= "<td>" . $tripPeriod->getActualIntervalAfterPreviousBus() . "</td>";

                            $bTableConstructor .= "</tr>";
                        }
                    }
                }
                $aTableConstructor .= "</table>";
                $bTableConstructor .= "</table>";


                $bigTableConstructor = "<table>"
                        . "<thead>"
                        . "<th>A_B</th>"
                        . "<th>B_A</th>"
                        . "</thead>";
                $bigTableConstructor .= "<tr>";
                $bigTableConstructor .= "<td style='vertical-align:top'>" . $aTableConstructor . "</td>";
                $bigTableConstructor .= "<td style='vertical-align:top'>" . $bTableConstructor . "</td>";
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
