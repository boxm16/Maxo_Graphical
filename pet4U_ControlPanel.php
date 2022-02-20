<?php
require_once 'Pet4U/DataBaseTools.php';
$dataBaseTools = new DataBaseTools();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="createTables">
            <button type="submit">Create Tables</button>

        </form>
        <?php
        if (isset($_POST["createTables"])) {
            //precedence is important, there are primary-foreign keys rstrictions
            $dataBaseTools->createRouteTable();
            $dataBaseTools->createTripVoucherTable();
            $dataBaseTools->createTripPeriodTable();
            $dataBaseTools->createLastUploadTable();
            $dataBaseTools->createTechTable();
            $dataBaseTools->createReportTechTable();
            $dataBaseTools->createReportsRoutesDatesTable();
        }
        ?>
    </body>
</html>
