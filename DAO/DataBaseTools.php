<?php

require_once 'DataBaseConnection.php';

class DataBaseTools {

    private $connection;

    function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->connection = $dataBaseConnection->getlocalhostConnection();
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

}
