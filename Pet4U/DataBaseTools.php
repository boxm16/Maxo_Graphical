<?php

require_once 'DAO/DataBaseConnection.php';

class DataBaseTools {

    private $connection;

    function __construct() {

        $dataBaseConnection = new DataBaseConnection();
        if ($_SERVER["SERVER_NAME"] == 'localhost') {
            echo 'localhost';
            $this->connection = $dataBaseConnection->getLocalhostConnection();
        } else {
            echo 'out server';
            $this->connection = $dataBaseConnection->getPDO();
        }
    }

    public function createRouteTable() {
        $sql = "CREATE TABLE `route` (
  `number` VARCHAR(10) NOT NULL,
  `prefix` int(4) NOT NULL, 
  `suffix` INT(3) NULL,
  `a_point` VARCHAR(100) NULL,
  `b_point` VARCHAR(100) NULL,
  `scheme` VARCHAR(5000) NULL,
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
        $sql = "CREATE TABLE `trip_voucher` (
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
    REFERENCES `route` (`number`)
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
        $sql = "CREATE TABLE `trip_period` (
  `trip_voucher_number` VARCHAR(20) NOT NULL,
  `type` VARCHAR(15) NOT NULL,
  `start_time_scheduled` TIME(0) NULL  DEFAULT NULL,
  `start_time_actual` TIME(0) NULL  DEFAULT NULL,
  `start_time_difference` VARCHAR(10) NULL,
  `arrival_time_scheduled` TIME(0) NULL  DEFAULT NULL,
  `arrival_time_actual` TIME(0) NULL  DEFAULT NULL,
  `arrival_time_difference` VARCHAR(10) NULL,
    CONSTRAINT `trip_voucher`
    FOREIGN KEY (`trip_voucher_number`)
    REFERENCES `trip_voucher` (`number`)
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

    public function createLastUploadTable() {
        $sql = "CREATE TABLE `last_upload` (
  `number` VARCHAR(10) NOT NULL,
  `date_stamp` DATE NOT NULL,
   CONSTRAINT `number`
    FOREIGN KEY (`number`)
    REFERENCES `route` (`number`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
";
        try {
            $this->connection->exec($sql);
            echo "Table 'last_upload' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'last_upload' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function createTechTable() {
        $sql = "CREATE TABLE `tech` (
  `tech_type` VARCHAR(15) NOT NULL,
  `value` TINYINT(1) NOT NULL,
  `start_row` INT(6) NOT NULL);
  INSERT INTO `tech` (tech_type, value,  start_row)
  VALUES('loading',0,8);
";
        try {
            $this->connection->exec($sql);
            echo "Table 'tech' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'tech' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function createReportTechTable() {
        $sql = "CREATE TABLE `report_tech` (
  `report_type` VARCHAR(15) NOT NULL,
  `id` INT NOT NULL AUTO_INCREMENT,
  `start_row_number` VARCHAR(10) NOT NULL,
  `percentage` INT NULL,
   PRIMARY KEY (`id`));
   ";
        try {
            $this->connection->exec($sql);
            echo "Table 'report_tech' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'report_tech' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function createReportsRoutesDatesTable() {
        $sql = "CREATE TABLE `reports_routes_dates` (
    `report_id` INT NOT NULL,
    `route_number` VARCHAR(10) NOT NULL,
    `date_stamp` DATE NOT NULL,
    CONSTRAINT `report_id`
    FOREIGN KEY (`report_id`)
    REFERENCES `report_tech` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);";



        try {
            $this->connection->exec($sql);
            echo "Table 'reports_routes_dates' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'reports_routes_dates' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    public function insertRoutes($routesData) {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO route (number, prefix, suffix, a_point, b_point)', $routesData);
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
                $dateStamp = $this->convertDateStamp($dateStamp);

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
                            if ($startTimeActual == "") {
                                $startTimeActual = null;
                            }
                            $startTimeDifference = $tripPeriod->getStartTimeDifference();
                            $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                            $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                            if ($arrivalTimeActual == "") {
                                $arrivalTimeActual = null;
                            }
                            $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();

                            $row = array($tripVoucherNumber, $type, $startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);

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

    public function insertLastUploadedRouteDates($routeDatesData) {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO last_upload (number, date_stamp)', $routeDatesData);
            $stmt->multiExecute($routeDatesData);
            $this->connection->commit();
            echo "New data (Last Uploaded Routes And Dates) inserted successfully into database" . "<br>";
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
            $resultSet = $this->connection->query($sql)->fetchAll();
            $returnArray = array();
            foreach ($resultSet as $row) {
                array_push($returnArray, $row["number"]);
            }
            return $returnArray;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getFullRoutes() {

        try {
            $sql = "SELECT * FROM route t1 INNER JOIN trip_voucher t2 ON t1.number=t2.route_number INNER JOIN trip_period t3 ON t2.number=t3.trip_voucher_number ORDER BY prefix, suffix;";
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

    public function getRequestedRoutesAndDates($requestedRoutesAndDates) {


        $routesAndDates = explode(",", $requestedRoutesAndDates);

        if (count($routesAndDates) > 0) {
            $firstRouteAndDate = array_shift($routesAndDates);
            $d = explode(":", $firstRouteAndDate);
            $firstRoutNumber = $d[0];
            $firstDate = $d[1];
            if (strpos($firstDate, "/")) {
                $firstDate = date_create_from_format("d/m/Y", $firstDate)->format("Y-m-d");
            }

            $sql = "SELECT * FROM route t1 INNER JOIN trip_voucher t2 ON t1.number=t2.route_number INNER JOIN trip_period t3 ON t2.number=t3.trip_voucher_number WHERE route_number='$firstRoutNumber' AND date_stamp='$firstDate' ";


            foreach ($routesAndDates as $routeAndDate) {
                if ($routeAndDate != "") {
                    $d = explode(":", $routeAndDate);
                    $routNumber = $d[0];
                    $date = $d[1];

                    if (strpos($date, "/")) {
                        $date = date_create_from_format("d/m/Y", $date)->format("Y-m-d");
                    }
                    $sql .= "OR route_number='$routNumber' AND date_stamp='$date' ";
                }
            }
            $sql .= " ORDER BY prefix, suffix;";
        }

        try {
            $result = $this->connection->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
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
    }

    public function getRouteForExodus($routeNumber, $dateStamp, $exodusNumber) {

        $sql = "SELECT * FROM route t1 INNER JOIN trip_voucher t2 ON t1.number=t2.route_number INNER JOIN trip_period t3 ON t2.number=t3.trip_voucher_number WHERE route_number='$routeNumber' AND date_stamp='$dateStamp' AND exodus_number=$exodusNumber ";

        try {
            $result = $this->connection->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
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
    }

    public function getRouteForDay($routeNumber, $dateStamp) {

        $sql = "SELECT * FROM route t1 INNER JOIN trip_voucher t2 ON t1.number=t2.route_number INNER JOIN trip_period t3 ON t2.number=t3.trip_voucher_number WHERE route_number='$routeNumber' AND date_stamp='$dateStamp';";

        try {
            $result = $this->connection->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
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
        if ($tripPeriodType != "baseLeaving" && $tripPeriodType != "baseLeaving_A" && $tripPeriodType != "baseLeaving_B") {
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
        if ($startTimeActual == null) {
            $startTimeActual = "";
        }
        $startTimeDifference = $row["start_time_difference"];
        $arrivalTimeScheduled = $row["arrival_time_scheduled"];
        $arrivalTimeActual = $row["arrival_time_actual"];
        if ($arrivalTimeActual == null) {
            $arrivalTimeActual = "";
        }
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

    public function getRoutePoints() {

        $routes = array();
        try {
            $sql = "SELECT  *  FROM route ORDER BY prefix, suffix ";
            $result = $this->connection->query($sql)->fetchAll();
            foreach ($result as $row) {
                $route = new RouteXL();

                $route->setNumber($row["number"]);
                $route->setAPoint($row["a_point"]);
                $route->setBPoint($row["b_point"]);
                $routes[$row["number"]] = $route;
            }

            return $routes;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function changeRouteNames($routeNumber, $aPoint, $bPoint) {
        $sql = "UPDATE route SET a_point=?, b_point=? WHERE number=?";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(1, $aPoint);
            $statement->bindParam(2, $bPoint);
            $statement->bindParam(3, $routeNumber);
            $statement->execute();
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
            echo "Records that are to be renewed deleted successfully <br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function deleteVouchers_2(array $vouchersForDeletion) {
        //here is array of objects, in first version is just string
        $firstVoucher = array_shift($vouchersForDeletion);
        $firstVoucherNumber = $firstVoucher[0];
        $sql = "DELETE FROM trip_voucher WHERE number='$firstVoucherNumber' ";
        foreach ($vouchersForDeletion as $voucher) {
            $firstVoucherNumber = $voucher[0];
            $sql .= " OR number='$firstVoucherNumber' ";
        }
        $sql .= ";";
        try {
            $this->connection->exec($sql);
            echo "Records that are to be renewed deleted successfully <br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    //------------------------------
    private function convertDateStamp($dateStamp) {
        $time = strtotime(str_replace('/', '-', $dateStamp));
        return $dateStamp = date('Y-m-d', $time);
    }

//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--
////--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--
////--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--//--
    //------------------------------


    public function registerNextChunk(int $endRow) {

        $sql = "UPDATE tech SET start_row=? WHERE tech_type='loading';";

        try {
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(1, $endRow);
            $statement->execute();
            echo "Next chunk with EndRow=$endRow registered successfully into database <br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getStartRowIndex() {
        $sql = "SELECT start_row FROM tech WHERE tech_type='loading'";

        try {
            $result = $this->connection->query($sql)->fetch();
            return $result["start_row"];
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getLastUploadedRouteDates() {
        $sql = "SELECT  number, date_stamp FROM last_upload";
        $routeDates = array();
        try {

            $result = $this->connection->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
        foreach ($result as $row) {
            $routeNumber = $row["number"];
            $date_stamp = $row["date_stamp"];
            $routeNumberDateStamp = $routeNumber . ":" . $date_stamp;
            array_push($routeDates, $routeNumberDateStamp);
        }
        return $routeDates;
    }

    public function resetTechTable() {
        $sql = "UPDATE tech SET value=0, start_row=8 WHERE tech_type='loading';";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function uploadRouteNames($routes) {
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();
            $aPoint = $route->getAPoint();
            $bPoint = $route->getBPoint();
            $this->changeRouteNames($routeNumber, $aPoint, $bPoint);
        }
    }

    public function uploadRouteSchemes($routes) {
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();
            $routeScheme = $route->getScheme();
            $sql = "UPDATE route SET scheme=? WHERE number=?";
            try {
                $statement = $this->connection->prepare($sql);
                $statement->bindParam(1, $routeScheme);
                $statement->bindParam(2, $routeNumber);
                $statement->execute();
            } catch (\PDOException $e) {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    //table deletions

    public function dropRouteTable() {
        $sql = "DROP TABLE route;";
        try {
            $this->connection->exec($sql);
            echo "Table 'route' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function dropTripVoucherTable() {
        $sql = "DROP TABLE trip_voucher;";
        try {
            $this->connection->exec($sql);
            echo "Table 'trip_voucher' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function dropTripPeriodTable() {
        $sql = "DROP TABLE trip_period;";
        try {
            $this->connection->exec($sql);
            echo "Table 'trip_period' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function dropLastUploadTable() {
        $sql = "DROP TABLE last_upload;";
        try {
            $this->connection->exec($sql);
            echo "Table 'last_upload' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function dropTechTable() {
        $sql = "DROP TABLE tech;";
        try {
            $this->connection->exec($sql);
            echo "Table 'tech' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function dropReportTechTable() {
        $sql = "DROP TABLE report_tech;";
        try {
            $this->connection->exec($sql);
            echo "Table 'report_tech' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function dropReportsRoutesDatesTable() {
        $sql = "DROP TABLE reports_routes_dates;";
        try {
            $this->connection->exec($sql);
            echo "Table 'reports_routes_dates' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getRoutesNamesAndSchemes() {
        try {
            $sql = "SELECT * FROM route;";
            $result = $this->connection->query($sql)->fetchAll();
            $routes = array();
            foreach ($result as $row) {
                $routeNumber = $row["number"];
                $aPoint = $row["a_point"];
                $bPoint = $row["b_point"];
                $scheme = $row["scheme"];
                $newRoute = new RouteGuaranteed();
                $newRoute->setNumber($routeNumber);
                $newRoute->setAPoint($aPoint);
                $newRoute->setBPoint($bPoint);
                $newRoute->setScheme($scheme);
                $routes[$routeNumber] = $newRoute;
            }
            return $routes;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
