<?php

require_once 'DAO/DataBaseConnection.php';
$s = microtime(true);
$dataBaseConnection = new DataBaseConnection();
$pdo = $dataBaseConnection->getPDO2();
$stmt = $pdo->query("SELECT * FROM join_table WHERE trip_voucher_number='A-838076715' LIMIT 10000")->fetchAll();
$e = microtime(true);
echo ($e - $s);
echo "<br>";
echo count($stmt);
