<?php

require_once 'Controller/GuarantyController.php';
if (isset($_POST["guaranteedClear"])) {
    $guarantyController = new GuarantyController();
    $guarantyController->getGuarantyRoutes();
}
?>