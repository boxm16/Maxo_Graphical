<?php
require_once './Controller/RouteXLController.php';

if (!isset($GLOBASL["routes"])) {
    $routeController = new RouteXLController();
    $routes = $GLOBALS["routes"];
} else {
    $routes = $GLOBALS["routes"];
}

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        echo "Exodus HERE";
        ?>
    </body>
</html>
