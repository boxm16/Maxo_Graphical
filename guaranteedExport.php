<?php

require_once 'Controller/GuarantyController.php';
if (isset($_POST["guaranteedClear"])) {
    $fileName = str_replace("\"", "\'", $_POST["fileName"]);
    $guarantyController = new GuarantyController();
    $guarantyController->getGuarantyRoutes($fileName);
}
?>