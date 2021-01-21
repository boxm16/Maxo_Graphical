<?php
session_start();
if (isset($_POST["routeNumber"]) && isset($_POST["dates"])) {
    $_SESSION["routeNumber"] = $_POST["routeNumber"];
    $_SESSION["dates"] = $_POST["dates"];
    $selectedRouteNumber = $_POST["routeNumber"];
    $selectedDates = $_POST["dates"];
} else {
    if (isset($_SESSION["routeNumber"]) && isset($_SESSION["dates"])) {

        $selectedRouteNumber = $_SESSION["routeNumber"];
        $selectedDates = $_SESSION["dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <?php
        var_dump($_SESSION["routeNumber"]);
        echo "<hr>";
        var_dump($_SESSION["dates"])
        ?>
    </body>
</html>
