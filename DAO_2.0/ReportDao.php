<?php

require_once 'DataBaseConnection.php';

class ReportDao {

    function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->connection = $dataBaseConnection->getLocalhostConnection();
    }

    public function registerRouteDetailsReport() {
        $sql = "INSERT INTO report_tech (report_type, start_row_number) VALUES ('routeDetails' , 1)";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
