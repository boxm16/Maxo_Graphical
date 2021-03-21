<?php
require_once 'Controller/RouteDBController.php';
require_once 'Controller/RouteXLController.php'; //one of this imports needed
require_once 'clientId.php';
session_start();
if (isset($_POST["routes:dates"])) {
    $_SESSION["routes:dates"] = $_POST["routes:dates"];
    $requestedRoutesAndDates = $_POST["routes:dates"];
} else {
    if (isset($_SESSION["routes:dates"])) {

        $requestedRoutesAndDates = $_SESSION["routes:dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}
$s = microtime(true);

$routeController = new RouteDBController();
$excelFormPackage = $routeController->getExcelFormPackage($requestedRoutesAndDates);

/*
$routeController = new RouteXLController();
$excelFormPackage = $routeController->getExcelFormPackage($clientId, $requestedRoutesAndDates);
*/

$e = microtime(true);
echo "Time required=" . ($e - $s);
$routes = $excelFormPackage["routes"];

$routeNumberPackage = $excelFormPackage["routeNumberPackage"];
$dateStampPackage = $excelFormPackage["dateStampPackage"];
$busNumberPackage = $excelFormPackage["busNumberPackage"];
$exodusNumberPackage = $excelFormPackage["exodusNumberPackage"];
$driverNamePackage = $excelFormPackage["driverNamePackage"];
$tripPeriodTypePackage = $excelFormPackage["tripPeriodTypePackage"];
$startTimeActualPackage = $excelFormPackage["startTimeActualPackage"];
$startTimeScheduledPackage = $excelFormPackage["startTimeScheduledPackage"];
$arrivalTimeScheduledPackage = $excelFormPackage["arrivalTimeScheduledPackage"];
$arrivalTimeActualPackage = $excelFormPackage["arrivalTimeActualPackage"];
$tripPeriodScheduledTimePackage = $excelFormPackage["tripPeriodScheduledPackage"];
$tripPeriodActualTimePackage = $excelFormPackage["tripPeriodActualPackage"];
$tripPeriodDifferenceTimePackage = $excelFormPackage["tripPeriodDifferenceTimePackage"];
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        echo "averages here";
        ?>
    </body>
</html>
