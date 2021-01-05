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
        
                foreach ($dayIntervals as $scheduledIntervals) {

                    foreach ($scheduledIntervals as $tripPeriod) {
                        echo $tripPeriod->getType() . ")";
                        echo $tripPeriod->getStartTimeScheduled();
                        echo "--";
                        echo $tripPeriod->getScheduledIntervalAfterPreviousBus();
                        echo "++";
                        echo $tripPeriod->getActualIntervalAfterPreviousBus();

                        echo "<br>";
                    }

                    echo "END OF TRIP PERIOD TYPE";
                    echo "<br>";
                }
                echo "---------------------------END OF DAY-----------------------------<hr>";
            }
            echo "<hr>++++++++++++++++END OF ROUTE++++++++++++++<hr>";
        }
        ?>
    </body>
</html>
