<?php
require_once 'Controller/RouteXLController.php';
$routeController = new RouteXLController();
$routes = $routeController->getScheduledIntervalsByDays();
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
            <a href="index.php">მთავარ გვერძე დაბრნუნება</a>
            <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a>
            <a href="trips.php">ბრუნები</a>
        </div>
        <?php
        echo "intervals here<br><br>";

        foreach ($routes as $route) {
            foreach ($route as $day) {

                $d = $day;
            
                foreach ($d as $tripPeriodsByType) {
                      
                    foreach ($tripPeriodsByType as $key => $tripPeriod) {
                     
                        echo $key . "--";
                        echo $tripPeriod->getStartTimeScheduled();
                        echo "<br>";
                    }
                }
                echo "<hr>";
            }echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
        }
        ?>
    </body>
</html>
