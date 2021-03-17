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
  `precedence` INT(3) NOT NULL,
  `a_point` VARCHAR(100) NULL,
  `b_point` VARCHAR(100) NULL,
   PRIMARY KEY (`number`),
   UNIQUE INDEX `precedence_UNIQUE` (`precedence` ASC) )
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
    ON DELETE CASCADE
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
    ON DELETE CASCADE
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

    public function insertRoutes($routesData) {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO route (number, precedence, a_point, b_point)', $routesData);
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

    public function insertUploadedData($routes) {
        $tripVouchersData = array();
        $tripPeriodsData = array();

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
                        array_push($tripVouchersData, $row);

                        foreach ($tripPeriods as $tripPeriod) {
                            $type = $tripPeriod->getType();
                            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                            $startTimeActual = $tripPeriod->getStartTimeActual();
                            $startTimeDifference = $tripPeriod->getStartTimeDifference();
                            $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                            $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                            $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();

                            $row = array($tripVoucherNumber, $type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
                            if (count($row) != 8)
                                echo"KOKO<br>";
                            array_push($tripPeriodsData, $row);
                        }
                    }
                }
            }
        }


        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO trip_voucher (number, route_number, date_stamp, exodus_number, driver_number, driver_name, bus_number, bus_type, notes)', $tripVouchersData);
            $stmt->multiExecute($tripVouchersData);
            //---
            $chunkedArray = array_chunk($tripPeriodsData, 5000);
            foreach ($chunkedArray as $data) {
                $stmt2 = $this->connection->multiPrepare('INSERT INTO trip_period (trip_voucher_number, type, start_time_scheduled, start_time_actual, start_time_difference, arrival_time_scheduled, arrival_time_actual, arrival_time_difference)', $data);
                $stmt2->multiExecute($data);
            }
            $this->connection->commit();
            echo "New Data inserted successfully into database" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
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
            $sql = "SELECT * FROM route t1 INNER JOIN trip_voucher t2 ON t1.number=t2.route_number INNER JOIN trip_period t3 ON t2.number=t3.trip_voucher_number ORDER BY precedence";
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
        $tripVoucherNumber = $row["trip_voucher_number"];
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

    public function getRoutesDatesFromDataBase(): array {
        //return array of routes and dates in format route:date
        $routesDates = array();
        try {
            $sql = "SELECT DISTINCT route_number, date_stamp FROM trip_voucher";
            $result = $this->connection->query($sql)->fetchAll();
            foreach ($result as $row) {
                $route = $row["route_number"];
                $dateStamp = $row["date_stamp"];
                $entry = "$route:$dateStamp";
                array_push($routesDates, $entry);
            }
            return $routesDates;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    ///----------------------------------------//--------------------------------

    public function deleteVouchers(array $vouchersForDeletion) {
        $firstVoucher = array_shift($vouchersForDeletion);
        $sql = "DELETE FROM trip_voucher WHERE number='$firstVoucher' ";
        foreach ($vouchersForDeletion as $voucherNumber) {
            $sql .= " OR number='$voucherNumber' ";
        }
        $sql .= ";";
        try {
            $this->connection->exec($sql);
            echo "Records that are to be renewed deleted successfully";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
