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
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="createTables">
            <button type="submit">Create Table</button>

        </form>
        <?php
        if (isset($_POST["createTables"])) {
            //precedence is important, there are primary-foreign keys rstrictions
            $dataBaseTools->createRouteTable();
            $dataBaseTools->createTripVoucherTable();
            $dataBaseTools->createTripPeriodTable();
        }
        ?>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="insert">
            <button type="submit">INSERT STATEMENT</button>

        </form>
        <?php
        if (isset($_POST["insert"])) {
         $dataBaseTools->insert();
        }
        ?>
    </body>
</html>
