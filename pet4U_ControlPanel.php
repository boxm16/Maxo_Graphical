<?php
require_once 'DAO/DataBaseTools_Pet4U.php';
$dataBaseTools = new DataBaseTools_Pet4U();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <a href="pet4U.php"> Go Main Page</a>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="createTables">
            <button type="submit">Create Tables</button>

        </form>
        <?php
        if (isset($_POST["createTables"])) {
            //precedence is important, there are primary-foreign keys rstrictions
            $dataBaseTools->createItemeTable();
            $dataBaseTools->createInvoiceTable();
            $dataBaseTools->createInvoiceItemeTable();
        }
        ?>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="deleteTables">
            <button type="submit">Delete Tables</button>

        </form>
        <?php
        if (isset($_POST["deleteTables"])) {
            //precedence is important, there are primary-foreign keys rstrictions


            $dataBaseTools->deleteInvoiceItemTable();
            $dataBaseTools->deleteInvoiceTable();
            $dataBaseTools->deleteItemTable();
        }
        ?>
    </body>
</html>
