<?php
require_once 'Controller/RouteXLController.php';
$routeController = new RouteXLController();
$routeCasesOfConcurrentlyHaltedBuses = $routeController->routeCasesOfConcurrentlyHaltedBuses();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
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
            <a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
            <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a>
            <a href="trips.php">ბრუნები</a>
            <a href="intervals.php">ინტერვალები</a>
        </div>
        <br>
    <center><h1>ერთდროულად მდგომი ავტობუსები </h1></center>
    <?php
    foreach ($routeCasesOfConcurrentlyHaltedBuses as $dayCasesOfConcurrentlyHaltedBuses) {
        foreach ($dayCasesOfConcurrentlyHaltedBuses as $case) {
            foreach ($case as $tripPeriod) {
                $dateStamp=$tripPeriod->getTripPeriodDNA()->getDateStamp();
                $haltStartTime = $tripPeriod->getPreviosTripPeriodArrivalTimeActual();
                $haltEndTime = $tripPeriod->getStartTimeActual();
                $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
                $haltPoint = $tripPeriod->getDeparturePoint();
                $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
                echo $dateStamp."   ".$haltStartTime . "-" . $haltEndTime . " EXODUS NUMBER:" . $exodusNumber . " Stoped At Point:" . $haltPoint . "<br>";
            }
            echo "end of case <hr>";
        }
        echo "END OF A DAY<hr><hr><hr>";
    }
    ?>
</body>
</html>
