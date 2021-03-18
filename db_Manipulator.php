<?php
require_once 'DAO/DataBaseTools.php';
require_once 'Controller/RouteXLController.php';
$dataBaseTools = new DataBaseTools();
$routeController = new RouteXLController();
$clienId = '0';
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
            $index = 1;
            foreach ($routes as $route) {
                $routeNumber = $route->getNumber();
                $insertRow = array($routeNumber, $index, "A-პუნკტი", "B-პუნკტი");
                array_push($insertData, $insertRow);
                $index++;
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
                    //here i need thrick to convert time format 
                    $time = strtotime(str_replace('/', '-', $dateStamp));
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
                    //here i need thrick to convert time format 
                    $time = strtotime(str_replace('/', '-', $dateStamp));
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
            <input hidden name="selectFullRoutesFromDB">
            <button type="submit">SELECT FULL ROUTES FROM DATABASE</button>

        </form>

        <?php
        if (isset($_POST["selectFullRoutesFromDB"])) {
            $index = 0;
            $routes = $dataBaseTools->getFullRoutes();
            foreach ($routes as $route) {
                $days = $route->getDays();
                foreach ($days as $day) {
                    $exoduses = $day->getExoduses();
                    foreach ($exoduses as $exodus) {
                        $tripVouchers = $exodus->getTripVouchers();
                        foreach ($tripVouchers as $tripVoucher) {
                            $tripPeriods = $tripVoucher->getTripPeriods();
                            foreach ($tripPeriods as $tripPeriod) {
                                $index++;
                            }
                        }
                    }
                }
            }
            echo $index;
        }
        ?>

        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="selectFullRoutesFromFile">
            <button type="submit">SELECT FULL ROUTES FROM File</button>

        </form>

        <?php
        if (isset($_POST["selectFullRoutesFromFile"])) {
            $index = 0;
            $routes = $routeController->getFullRoutes($clienId);
            foreach ($routes as $route) {
                $days = $route->getDays();
                foreach ($days as $day) {
                    $exoduses = $day->getExoduses();
                    foreach ($exoduses as $exodus) {
                        $tripVouchers = $exodus->getTripVouchers();
                        foreach ($tripVouchers as $tripVoucher) {
                            $tripPeriods = $tripVoucher->getTripPeriods();
                            foreach ($tripPeriods as $tripPeriod) {
                                $index++;
                            }
                        }
                    }
                }
            }
            echo $index;
        }
        ?>

    </body>
</html>
