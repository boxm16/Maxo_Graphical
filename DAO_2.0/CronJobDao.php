<?php

require_once 'DAO/DataBaseConnection.php';

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

    public function registerNewUpload() {
        $sql = "UPDATE tech SET value=1, start_row=8 WHERE tech_type='loading';";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    public function deleteLastUploadedData() {
        $sql = "DELETE FROM last_upload;";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            echo "All data has been deleted successfully from last_upload table <br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

}
