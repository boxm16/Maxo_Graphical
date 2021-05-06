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
            $this->connection->beginTransaction();
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $lastInsertionId = $this->connection->lastInsertId();
            $this->connection->commit();
        } catch (PDOExecption $e) {
            $this->connection->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }
        return $lastInsertionId;
    }

    public function registerReportData($insertionData) {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->multiPrepare('INSERT INTO reports_routes_dates (report_id, route_number, date_stamp)', $insertionData);
            $stmt->multiExecute($insertionData);
            $this->connection->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
