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
    </head>
    <body>
        <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
        <?php
        foreach ($routes as $route) {

            $days = $route->getDays();
            echo "<hr>Route Number: " . $route->getNumber() . "<br>";


            foreach ($days as $day) {
                echo "Date: " . $day->getDateStamp() . "<br>";
                $exoduses = $day->getExoduses();
                foreach ($exoduses as $exodus) {
                    echo "Exodus Number: " . $exodus->getNumber() . "<br>";

                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {
                        echo "Voucher Number: " . $tripVoucher->getNumber();
                        echo "/// Bus Type: " . $tripVoucher->getBusType();
                        echo "/// Bus Number: " . $tripVoucher->getBusNumber();
                        echo "/// Driver Number: " . $tripVoucher->getDriverNumber();
                        echo "/// Driver Name: " . $tripVoucher->getDriverName()."<br>";
                        echo "Notes: " . $tripVoucher->getNotes() . "<br>";

                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {

                            echo "<hr>";
                            echo $tripPeriod->getType();
                            echo "<br>";
                        }
                    }
                }
                echo "-----------------DAY end-------------";
                echo "<hr><hr><hr>";
            }

            echo " ROUTE end ROUTE end ROUTE end ROUTE end ROUTE end";
            echo "<hr><hr><hr><hr>";
        }
        ?>

    </body>
</html>
