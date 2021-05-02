<?php

require_once 'DataBaseConnection.php';

class CronJobDao {

    private $connection;

    function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->connection = $dataBaseConnection->getLocalhostConnection();
    }

    public function isLoading(): bool {
        $isLoading;
        $sql = "SELECT value FROM tech WHERE tech_type='loading'";

        try {

            $result = $this->connection->query($sql)->fetchAll();
            foreach ($result as $row) {
                $isLoading = $row["value"];
            }
            return $isLoading;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
