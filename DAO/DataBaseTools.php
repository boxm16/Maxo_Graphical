<?php

require_once 'DataBaseConnection.php';
require_once 'Model/RouteXL.php';

class DataBaseTools {

    private $connection;

    function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->connection = $dataBaseConnection->getLocalhostConnection();
    }

    public function createRouteTable() {
        $sql = "CREATE TABLE `231185`.`route` (
  `number` VARCHAR(10) NOT NULL,
  `a_point` VARCHAR(100) NULL,
  `b_point` VARCHAR(100) NULL,
   PRIMARY KEY (`number`))
   ENGINE = InnoDB
   DEFAULT CHARACTER SET = utf8;
   ";
        try {
            $this->connection->exec($sql);
            echo "Table 'route' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'route' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function createTripVoucherTable() {
        $sql = "CREATE TABLE `231185`.`trip_voucher` (
    `number` VARCHAR(20) NOT NULL,
    `route_number` VARCHAR(10) NOT NULL,
    `date_stamp` DATE NOT NULL,
    `exodus_number` INT(2) NOT NULL,
    `driver_number` VARCHAR(10) NULL,
    `driver_name` VARCHAR(45) NULL,
    `bus_number` VARCHAR(15) NULL,
    `bus_type` VARCHAR(35) NULL,
    `notes` VARCHAR(2000) NULL,
    PRIMARY KEY (`number`),
    CONSTRAINT `route_number`
    FOREIGN KEY (`route_number`)
    REFERENCES `231185`.`route` (`number`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;
   ";
        try {
            $this->connection->exec($sql);
            echo "Table 'trip_voucher' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'trip_voucher' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function createTripPeriodTable() {
        $sql = "CREATE TABLE `231185`.`trip_period` (
  `trip_voucher_number` VARCHAR(20) NOT NULL,
  `type` VARCHAR(15) NOT NULL,
  `start_time_scheduled` VARCHAR(10) NULL,
  `start_time_actual` VARCHAR(10) NULL,
  `start_time_difference` VARCHAR(10) NULL,
  `arrival_time_scheduled` VARCHAR(10) NULL,
  `arrival_time_actual` VARCHAR(10) NULL,
  `arrival_time_difference` VARCHAR(10) NULL,
    CONSTRAINT `trip_voucher`
    FOREIGN KEY (`trip_voucher_number`)
    REFERENCES `231185`.`trip_voucher` (`number`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8
   ";
        try {
            $this->connection->exec($sql);
            echo "Table 'trip_period' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'trip_period' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function insert() {
        $routeNumber = '9';
        $a_point = "ისანი";
        $b_point = "ლოჭინი";
        $routeValuesArray = array($routeNumber, $a_point, $b_point);
        $tripVoucherNuber = "AA-1112";
        $dateStamp = "2020-12-01";
        $exodusNumber = 1;
        $driverNumber = "1126";
        $driverName = "Koka";
        $busNumber = "BUS-NUMBER-12";
        $busType = "SUZUKI";
        $notes = "lalalalallalalalalalalalalaalalalalalala";
        $tripPeriodValuesArray = array($tripVoucherNuber, $routeNumber, $dateStamp, $exodusNumber, $driverNumber, $driverName, $busNumber, $busType, $notes);
        $sql = "INSERT INTO route (number, a_point, b_point) VALUES (?,?,?)";
        $sql_1 = "INSERT INTO trip_voucher (number, route_number, date_stamp, exodus_number, driver_number, driver_name, bus_number, bus_type, notes) VALUES (?,?,?,?,?,?,?,?,?)";
        try {
            $this->connection->beginTransaction();
            $this->connection->prepare($sql)->execute($routeValuesArray);
            $this->connection->prepare($sql_1)->execute($tripPeriodValuesArray);
            $this->connection->commit();
            echo "route inserted successfully" . "<br>";
            echo "trip_period inserted successfully" . "<br>";
        } catch (\PDOException $e) {

            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function insertRoutes($routesData) {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO route (number, a_point, b_point)', $routesData);
            $stmt->multiExecute($routesData);
            // $stmt = $pdo->prepare("INSERT INTO trip_voucher (bus_number, bus_type) VALUES (?,?)");
            //$indexArray = array($index, $index);
            // $stmt->execute($indexArray);
            $this->connection->commit();
            echo "Routes inserted successfully into database" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function insertTripVouchers($tripVouchersData) {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO trip_voucher (number, route_number, date_stamp, exodus_number, driver_number, driver_name, bus_number, bus_type, notes)', $tripVouchersData);
            $stmt->multiExecute($tripVouchersData);
            // $stmt = $pdo->prepare("INSERT INTO trip_voucher (bus_number, bus_type) VALUES (?,?)");
            //$indexArray = array($index, $index);
            // $stmt->execute($indexArray);
            $this->connection->commit();
            echo "TripVouchers inserted successfully into database" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function insertTripPeriods($tripPeriodsData) {
        $chunkedArray = array_chunk($tripPeriodsData, 5000);
        foreach ($chunkedArray as $data) {
            try {
                $this->connection->beginTransaction();
                $stmt = $this->connection->multiPrepare('INSERT INTO trip_period (trip_voucher_number, type, start_time_scheduled, start_time_actual, start_time_difference, arrival_time_scheduled, arrival_time_actual, arrival_time_difference)', $data);
                $stmt->multiExecute($data);
                // $stmt = $pdo->prepare("INSERT INTO trip_voucher (bus_number, bus_type) VALUES (?,?)");
                //$indexArray = array($index, $index);
                // $stmt->execute($indexArray);
                $this->connection->commit();
                echo "Trip Periods inserted successfully into database" . "<br>";
            } catch (\PDOException $e) {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    //---------------------//------------//----------------
    //---------------------//------------//----------------
    //---------------------//------------//----------------
    //---------------------//------------//----------------
    //--------------------- //select part//------------------


    public function getRouteNumbers() {


        try {
            $sql = "SELECT number FROM route";
            $result = $this->connection->query($sql)->fetchAll();
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getFullRoutes() {

        try {
            $sql = "SELECT * FROM route t1 INNER JOIN trip_voucher t2 ON t1.number=t2.route_number INNER JOIN trip_period t3 ON t2.number=t3.trip_voucher_number";
            $result = $this->connection->query($sql)->fetchAll();
            $routes = array();
            foreach ($result as $row) {
                $routeNumber = $row["route_number"];
                if (key_exists($routeNumber, $routes)) {
                    $existingRoute = $routes[$routeNumber];
                    $refilledRoute = $this->addRowElementsToRoute($existingRoute, $row);
                    $routes[$routeNumber] = $refilledRoute;
                } else {
                    $newRoute = new RouteXL();
                    $newRoute->setNumber($routeNumber);
                    $refilledRoute = $this->addRowElementsToRoute($newRoute, $row);
                    $routes[$routeNumber] = $refilledRoute;
                }
            }

            return $routes;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    //route filling part

    private function addRowElementsToRoute($route, $row) {

        $dateStamp = $row["date_stamp"];
        $days = $route->getDays();
        if (array_key_exists($dateStamp, $days)) {
            $existingDay = $days[$dateStamp];
            $refilledDay = $this->addElementsToExistingDay($existingDay, $row);
            $days[$dateStamp] = $refilledDay;
        } else {
            $newDay = new DayXL();
            $newDay->setDateStamp($dateStamp);
            $refilledDay = $this->addElementsToExistingDay($newDay, $row);
            $days[$dateStamp] = $refilledDay;
        }
        $route->setDays($days);
        return $route;
    }

    private function addElementsToExistingDay($day, $row) {
        $exodusNumber = $row["exodus_number"];
        $exoduses = $day->getExoduses();
        if (array_key_exists($exodusNumber, $exoduses)) {
            $existingExodus = $exoduses[$exodusNumber];
            $refilledExodus = $this->addElementsToExistingExodus($existingExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        } else {
            $newExodus = new ExodusXL();
            $newExodus->setNumber($exodusNumber);
            $refilledExodus = $this->addElementsToExistingExodus($newExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        }
        $day->setExoduses($exoduses);
        return $day;
    }

    private function addElementsToExistingExodus($exodus, $row) {
        $tripVoucherNumber = $row["exodus_number"];
        $vouchers = $exodus->getTripVouchers();
        if (array_key_exists($tripVoucherNumber, $vouchers)) {
            $existingVoucher = $vouchers[$tripVoucherNumber];
            $refilledVoucher = $this->addElementsToExistingTripVoucher($existingVoucher, $row);
            $vouchers[$tripVoucherNumber] = $refilledVoucher;
        } else {
            $busNumber = $row["bus_number"];
            $busType = $row["bus_type"];
            $driverNumber = $row["driver_number"];
            $driverName = $row["driver_name"];
            $notes = $row["notes"];

            $newTripVoucher = new TripVoucherXL();
            $newTripVoucher->setNumber($tripVoucherNumber);
            $newTripVoucher->setBusNumber($busNumber);
            $newTripVoucher->setBusType($busType);
            $newTripVoucher->setDriverNumber($driverNumber);
            $newTripVoucher->setDriverName($driverName);
            $newTripVoucher->setNotes($notes);
            $refilledVoucher = $this->addElementsToExistingTripVoucher($newTripVoucher, $row);
            $vouchers[$tripVoucherNumber] = $refilledVoucher;
        }
        $exodus->setTripVouchers($vouchers);
        return $exodus;
    }

    private function addElementsToExistingTripVoucher($tripVoucher, $row) {
        $tripPeriods = $tripVoucher->getTripPeriods();

        $tripPeriod = $this->createTripPeriod($row);
        $tripPeriodType = $tripPeriod->getType();
        if ($tripPeriodType != "baseLeaving") {
            $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
        }
        array_push($tripPeriods, $tripPeriod);


        $tripVoucher->setTripPeriods($tripPeriods);
        return $tripVoucher;
    }

    private function createTripPeriod($row) {
        $type = $row["type"];
        $startTimeScheduled = $row["start_time_scheduled"];
        $startTimeActual = $row["start_time_actual"];
        $startTimeDifference = $row["start_time_difference"];
        $arrivalTimeScheduled = $row["arrival_time_scheduled"];
        $arrivalTimeActual = $row["arrival_time_actual"];
        $arrivalTimeDifference = $row["arrival_time_difference"];
        $tripPeriod = new TripPeriodXL($type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
        return $tripPeriod;
    }

    private function addPreviosTripPeriodTimes($tripPeriod, $tripPeriods) {

        $previousTripPeriod = $tripPeriods[count($tripPeriods) - 1];
        $previousTripPeriodArrivalTimeActual = $previousTripPeriod->getArrivalTimeActual();
        $previousTripPeriodArrivalTimeScheduled = $previousTripPeriod->getArrivalTimeScheduled();
        $tripPeriod->setPreviousTripPeriodArrivalTimeActual($previousTripPeriodArrivalTimeActual);
        $tripPeriod->setPreviousTripPeriodArrivalTimeScheduled($previousTripPeriodArrivalTimeScheduled);
        return $tripPeriod;
    }

}
