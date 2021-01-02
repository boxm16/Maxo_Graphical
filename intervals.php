<?php
require_once 'Controller/RouteXLController.php';
$routeController = new RouteXLController();
$routesWithScheduledIntervals = $routeController->getScheduledIntervalsByDays();
$routesWithActualIntervals = $routeController->getActualIntervalsByDays();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            .split {
                height: 100%;
                width: 50%;
                position: fixed;
                z-index: 1;
                top: 40;
                overflow-x: hidden;
                padding-top: 40px;
            }

            .left {
                left: 0;

            }

            .right {
                right: 0;

            }

            .centered {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
            }



            td{
                vertical-align:top;
            }
            table, th,  tr, td {
                border :1px solid black;
                border-collapse: collapse;
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
            <a href="index.php">მთავარ გვერდზე დაბრნუნება</a>
            <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a>
            <a href="trips.php">ბრუნები</a>
        </div>
        Intervals Here<br><br>     
        <div class="split left">
            <div><center><h3>გეგმიური ინტერვალი</h3></center></div>
            <?php
            foreach ($routesWithScheduledIntervals as $route) {

                foreach ($route as $day) {

                    $d = $day;
                    $tableConstructor = "<table name='dayTable'><th>A_B</th><th>B_A</th>";
                    $A = "<table name='A_B_trips'><th>გასვლის N</th><th>გასვლის დრო</th><th>ინტერვალი</th>";
                    $B = "<table name='B_A trips'><th>გასვლის N</th><th>გასვლის დრო</th><th>ინტერვალი</th>";
                    foreach ($d as $tripPeriodsByType) {



                        foreach ($tripPeriodsByType as $key => $tripPeriod) {
                            $exodusNumber = $tripPeriod->getParentExodusNumber();
                            $starTime = $tripPeriod->getStartTimeScheduled();
                            $interval = $tripPeriod->getScheduledIntervalFromPreviousTripPeriod();
                            if ($tripPeriod->getType() == "ab") {
                                $A .= "<tr><td>" . $exodusNumber . "</td><td>" . $starTime . "</td><td>" . $interval . "</td></tr>";
                            }
                            if ($tripPeriod->getType() == "ba") {
                                $B .= "<tr><td>" . $exodusNumber . "</td><td>" . $starTime . "</td><td>" . $interval . "</td></tr>";
                            }
                        }
                    }
                    $A .= "</table>";
                    $B .= "</table>";

                    $tableConstructor .= "<tr><td>" . $A . "</td><td>" . $B . "</td></tr></table>";
                    echo $tableConstructor;

                    echo "<hr>";
                }echo " +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
            }
            ?>
        </div>
        <div class="split right">
            <div><center><h3>ფაქტიურ ინტერვალი</h3></center></div>
            <?php
            foreach ($routesWithActualIntervals as $route) {

                foreach ($route as $day) {

                    $d = $day;
                    $tableConstructor = "<table name='dayTable'><th>A_B</th><th>B_A</th>";
                    $A = "<table name='A_B_trips'><th>გასვლის N</th><th>გასვლის დრო</th><th>ინტერვალი</th>";
                    $B = "<table name='B_A trips'><th>გასვლის N</th><th>გასვლის დრო</th><th>ინტერვალი</th>";
                    foreach ($d as $tripPeriodsByType) {



                        foreach ($tripPeriodsByType as $key => $tripPeriod) {
                            $exodusNumber = $tripPeriod->getParentExodusNumber();
                            $starTime = $tripPeriod->getStartTimeActual();
                            $interval = $tripPeriod->getActualIntervalFromPreviousTripPeriod();
                            if ($tripPeriod->getType() == "ab") {
                                $A .= "<tr><td>" . $exodusNumber . "</td><td>" . $starTime . "</td><td>" . $interval . "</td></tr>";
                            }
                            if ($tripPeriod->getType() == "ba") {
                                $B .= "<tr><td>" . $exodusNumber . "</td><td>" . $starTime . "</td><td>" . $interval . "</td></tr>";
                            }
                        }
                    }
                    $A .= "</table>";
                    $B .= "</table>";

                    $tableConstructor .= "<tr><td>" . $A . "</td><td>" . $B . "</td></tr></table>";
                    echo $tableConstructor;

                    echo "<hr>";
                }echo " +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
            }
            ?>
        </div>
    </body>
</html>
