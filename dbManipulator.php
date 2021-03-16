<?php
require_once 'DAO/DataBaseTools.php';
require_once 'Controller/RouteXLController.php';
$dataBaseTools = new DataBaseTools();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="createTables">
            <button type="submit">Create Table</button>

        </form>
        <?php
        if (isset($_POST["createTables"])) {
            //precedence is important, there are primary-foreign keys rstrictions
            $dataBaseTools->createRouteTable();
            $dataBaseTools->createTripVoucherTable();
            $dataBaseTools->createTripPeriodTable();
        }
        ?>

        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="insertRoutes">
            <button type="submit">INSERT Routes</button>

        </form>
        <?php
        if (isset($_POST["insertRoutes"])) {
            $s = microtime(true);
            $routeController = new RouteXLController();
            $clientId = "0";
            $routes = $routeController->getFullRoutes($clientId);
            $insertData = array();
            foreach ($routes as $route) {
                $routeNumber = $route->getNumber();
                $insertRow = array($routeNumber, "lapaluka", "zumbaland");
                array_push($insertData, $insertRow);
            }

            $dataBaseTools->insertRoutes($insertData);
            $e = microtime(true);
            echo "Time required:" . ($e - $s);
        }
        ?>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="insertTripVouchers">
            <button type="submit">INSERT Trip Vouchers</button>

        </form>
        <?php
        if (isset($_POST["insertTripVouchers"])) {
            $s = microtime(true);
            $routeController = new RouteXLController();
            $clientId = "0";
            $routes = $routeController->getFullRoutes($clientId);
            $insertData = array();
            foreach ($routes as $route) {
                $routeNumber = $route->getNumber();
                $days = $route->getDays();
                foreach ($days as $day) {
                    $dateStamp = $day->getDateStamp();
                    $time = strtotime($dateStamp);
                    $dateStamp = date('Y-m-d', $time);

                    $exoduses = $day->getExoduses();
                    foreach ($exoduses as $exodus) {
                        $exodusNumber = $exodus->getNumber();
                        $tripVouchers = $exodus->getTripVouchers();
                        foreach ($tripVouchers as $tripVoucher) {
                            $tripPeriods = $tripVoucher->getTripPeriods();
                            $tripVoucherNumber = $tripVoucher->getNumber();
                            $busNumber = $tripVoucher->getBusNumber();
                            $busType = $tripVoucher->getBusType();
                            $driverNumber = $tripVoucher->getDriverNumber();
                            $driverName = $tripVoucher->getDriverName();
                            $notes = $tripVoucher->getNotes();

                            $row = array($tripVoucherNumber, $routeNumber, $dateStamp, $exodusNumber, $driverNumber, $driverName, $busNumber, $busType, $notes);
                            array_push($insertData, $row);
                            /*
                              foreach ($tripPeriods as $tripPeriod) {
                              $type = $tripPeriod->getType();
                              $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                              $startTimeActual = $tripPeriod->getStartTimeActual();
                              $startTimeDifference = $tripPeriod->getStartTimeDifference();
                              $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                              $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                              $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();
                              $tp = array($routeNumber, $dateStamp, $exodusNumber, $tripVoucherNumber, $driverNumber, $driverName, $busNumber, $busType, $startTimeScheduled, $startTimeScheduled, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference, $type, $notes);
                              array_push($tripPeriodsData, $tp);
                              }

                             */
                        }
                    }
                }
            }

            $dataBaseTools->insertTripVouchers($insertData);
            $e = microtime(true);
            echo "Time required:" . ($e - $s);
        }
        ?>



        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="insertTripPeriods">
            <button type="submit">INSERT Trip Periods</button>

        </form>
        <?php
        if (isset($_POST["insertTripPeriods"])) {
            $s = microtime(true);
            $routeController = new RouteXLController();
            $clientId = "0";
            $routes = $routeController->getFullRoutes($clientId);
            $insertData = array();
            foreach ($routes as $route) {
                $routeNumber = $route->getNumber();
                $days = $route->getDays();
                foreach ($days as $day) {
                    $dateStamp = $day->getDateStamp();
                    $time = strtotime($dateStamp);
                    $dateStamp = date('Y-m-d', $time);

                    $exoduses = $day->getExoduses();
                    foreach ($exoduses as $exodus) {
                        $exodusNumber = $exodus->getNumber();
                        $tripVouchers = $exodus->getTripVouchers();
                        foreach ($tripVouchers as $tripVoucher) {
                            $tripPeriods = $tripVoucher->getTripPeriods();
                            $tripVoucherNumber = $tripVoucher->getNumber();
                            $busNumber = $tripVoucher->getBusNumber();
                            $busType = $tripVoucher->getBusType();
                            $driverNumber = $tripVoucher->getDriverNumber();
                            $driverName = $tripVoucher->getDriverName();
                            $notes = $tripVoucher->getNotes();



                            foreach ($tripPeriods as $tripPeriod) {
                                $type = $tripPeriod->getType();
                                $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                $startTimeActual = $tripPeriod->getStartTimeActual();
                                $startTimeDifference = $tripPeriod->getStartTimeDifference();
                                $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                                $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                                $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();

                                $row = array($tripVoucherNumber, $type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
                                array_push($insertData, $row);
                            }
                        }
                    }
                }
            }

            $dataBaseTools->insertTripPeriods($insertData);
            $e = microtime(true);
            echo "Time required:" . ($e - $s);
        }
        ?>



        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="insert">
            <button type="submit">INSERT STATEMENT</button>

        </form>
        <?php
        if (isset($_POST["insert"])) {
            $dataBaseTools->insert();
        }
        ?>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="selectRouteNumbers">
            <button type="submit">SELECT ALL ROUTE NUMBERS</button>

        </form>
        <?php
        if (isset($_POST["selectRouteNumbers"])) {
            $routeNumbers = $dataBaseTools->getRouteNumbers();
            foreach ($routeNumbers as $row) {
                echo $row["number"];
                echo "<br>";
            }
        }
        ?>


        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="selectFullRoutes">
            <button type="submit">SELECT FULL ROUTES</button>

        </form>
        <?php
        if (isset($_POST["selectFullRoutes"])) {
            $s= microtime(true);
            $routes = $dataBaseTools->getFullRoutes();
          foreach($routes as $route){
              echo $routeNumber=$route->getNumber();echo "<br>";
          }
            $e = microtime(true);
            echo "<br>Time required:" . ($e - $s);
        }
        ?>

    </body>
</html>
