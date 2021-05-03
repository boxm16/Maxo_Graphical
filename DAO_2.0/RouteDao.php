<?php

require_once 'DataBaseConnection.php';

class RouteDao {

    private $connection;

    function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->connection = $dataBaseConnection->getLocalhostConnection();
    }

    public function getLastUploadedRoutesDates() {
        try {
            $sql = "SELECT DISTINCT t1.number, date_stamp FROM last_upload t1 INNER JOIN route t2 ON t1.number=t2.number ORDER BY prefix, suffix, date_stamp DESC";
            $result = $this->connection->query($sql)->fetchAll();
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getAllUploadedRoutesDates() {
        try {
            $sql = "SELECT DISTINCT route_number, date_stamp FROM trip_voucher t1 INNER JOIN route t2 ON t1.route_number=t2.number ORDER BY prefix, suffix, date_stamp DESC";
            $result = $this->connection->query($sql)->fetchAll();
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
