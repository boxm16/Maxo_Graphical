<?php
require_once 'Pet4U/DataBaseTools_pet4U.php';
$dataBaseTools = new DataBaseTools_pet4U();
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
            $dataBaseTools->createItemeTable();
            $dataBaseTools->createInvoiceTable();
            $dataBaseTools->createInvoiceItemeTable();
        }
        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input hidden name="deleteTables">
            <button type="submit">Delete Tables</button>

        </form>
        <?php
        if (isset($_POST["deleteTables"])) {
            //precedence is important, there are primary-foreign keys rstrictions


            $dataBaseTools->deleteInvoiceItemTable();
            $dataBaseTools->deleteInvoiceTable();
           //    $dataBaseTools->deleteItemTable();
        }
        ?>
    </body>
</html>
