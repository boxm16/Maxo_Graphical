<?php
require_once 'DAO/DataBaseTools.php';
$dataBaseTools = new DataBaseTools();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        //precedence is important, there ara primary-foreign keys rstrictions
        $dataBaseTools->createRouteTable();
        $dataBaseTools->createTripVoucherTable();
        $dataBaseTools->createTripPeriodTable();
        ?>
    </body>
</html>
