<?php
require_once 'Controller_2.0/ReportController.php';
$reportController = new ReportController();
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
        ?>

    </body>
</html>
