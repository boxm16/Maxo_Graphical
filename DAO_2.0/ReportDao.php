<?php

require_once 'DAO/DataBaseConnection.php';

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

    public function unregisterRouteDetailsReport($reportId) {
        $sql = "DELETE FROM report_tech WHERE id=$reportId;";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            echo "Report has been deleted from database <br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function getRequestedRoutesAndDatesLimited($requestedRoutesAndDates) {

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
        return $result;
    }

    public function getRegisteredRouteDetailsReportLimitedData($reportId, $limit) {


        $sql = "SELECT * FROM trip_period t1 INNER JOIN trip_voucher t2 ON t1.trip_voucher_number=t2.number INNER JOIN reports_routes_dates t3 ON t2.date_stamp=t3.date_stamp AND t2.route_number=t3.route_number INNER JOIN report_tech t4 ON t4.id=t3.report_id WHERE  t4.id=$reportId LIMIT $limit ;";

        try {
            $result = $this->connection->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
        return $result;
    }

}
