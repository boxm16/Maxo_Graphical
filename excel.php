<?php
require_once 'Controller/RouteXLController.php';
$routeController = new RouteXLController();
$routes = $routeController->getRoutes();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>

            table, th, td {
                border: 1px solid black;
                border-collapse: collapse
            }

        </style>
    </head>
    <body>
        <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
        <?php
        foreach ($routes as $route) {
            echo "<hr>მარშრუტი N: " . $route->getNumber() . "<br>";
            $days = $route->getDays();

            foreach ($days as $day) {

                echo $day->getDateStamp() . "<hr>";

                $exoduses = $day->getExoduses();

                foreach ($exoduses as $exodus) {
                    $tableConstructor = "<table style='width:400px'><tbody>";

                    $tableConstructor .= "<tr><td colspan='7' style=' text-align: center; '>გასვლა N:" . $exodus->getNumber() . "</td></tr>";
                    $tripVouchers = $exodus->getTripVouchers();
                    foreach ($tripVouchers as $tripVoucher) {


                        $tableConstructor .= "<tr><td colspan='7'>საგზურის N:" . $tripVoucher->getNumber()
                                . " //ავტობუსის ტიპი:" . $tripVoucher->getBusType()
                                . " //ავტობუსის N: " . $tripVoucher->getBusNumber()
                                . " //მძღოლისN " . $tripVoucher->getDriverNumber()
                                . " //მძღოლი:" . $tripVoucher->getDriverName() . "</td></tr>"
                                . "<tr ><td colspan='7'> შენიშვნები:" . $tripVoucher->getNotes() . "</td>"
                                . "</tr>";
                        $tableConstructor .= "<tr>"
                                . "<th colspan='3'>გასვლის დრო</th>"
                                . "<th></th>"
                                . "<th colspan='3'>მისვლის დრო</th>"
                                . "</tr>"
                                . "<tr>"
                                . "<th>გეგმიუირი</th>"
                                . "<th>ფაქთიური</th>"
                                . "<th>სხვაობა</th>"
                                . "<th>------</th>"
                                . "<th>გეგმიუირი</th>"
                                . "<th>ფაქთიური</th>"
                                . "<th>სხვაობა</th>"
                                . "</tr>";
                        $tripPeriods = $tripVoucher->getTripPeriods();
                        foreach ($tripPeriods as $tripPeriod) {

                            $tableConstructor .= "<tr>"
                                    . "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                    . "<td>" . $tripPeriod->getStartTimeActual() . "</td>"
                                    . "<td>" . $tripPeriod->getStartTimeDifference() . "</td>"
                                    . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                    . "</tr>";
                        }
                    }
                    $tableConstructor .= "</tbody></table>";
                    echo $tableConstructor;

                    echo "<br>";
                }
            }
        }
        ?>

    </body>
</html>
