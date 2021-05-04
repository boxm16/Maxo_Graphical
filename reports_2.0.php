<?php
require_once 'Controller_2.0/ReportController.php';
$reportController = new ReportController();

if (isset($_POST["routeDetailsReport"]) || isset($_POST["intervalsReport"]) || isset($_POST["excelFormReport"])) {
    $requestedReportsData = $_POST;
    $reportController->registerReports($requestedReportsData);
}
$reportList = $reportController->getReportList();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        foreach ($reportList as $reportName) {
            if ($reportName != "." && $reportName != "..") {
                echo $reportName;
                echo "<br>";
            }
        }

        $s = microtime(true);
     

        echo"<hr>";


        var_dump($_POST);
        $e = microtime(true);
        echo "Time needed:".($e - $s);
        ?>



    </body>
</html>
