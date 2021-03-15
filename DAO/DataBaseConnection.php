<?php

require_once 'mPDO.php';

class DataBaseConnection {

    public function getPDO() {



        $host = 'remotemysql.com';
        $db = '9w706j5s1P';
        $user = '9w706j5s1P';
        $pass = 'zEcc9jTAyc';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Eroor Code:";
            echo $e->getCode();
        }
    }

    public function getlocalhostConnection() {
        $host = 'localhost';
        $db = '231185';
        $user = '231185';
        $pass = 'athina2004';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode();
        }
    }

}
