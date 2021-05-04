<?php

require_once 'DataBaseConnection.php';

class ReportDao {

    function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->connection = $dataBaseConnection->getLocalhostConnection();
    }

}
