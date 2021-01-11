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
    </head>
    <body>
        <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
        <table id="header-fixed">
            <thead>

                <tr>
                    <th>გეგმიუირი<br>გასვლის<br>დრო</th>
                    <th>ფაქტიური<br>გასვლის<br>დრო</th>
                    <th>სხვაობა</th>
                    <th>------</th>
                    <th>გეგმიუირი<br>მისვლის<br>დრო</th>
                    <th>ფაქტიური<br>მისვლის<br>დრო</th>
                    <th>სხვაობა</th>
                    <th></th>
                    <th>წირის<br>გეგმიური<br>დრო</th>
                    <th>წირის<br>ფაქტიური<br>დრო</th>
                    <th>დგომის<br>გეგმიური<br> დრო</th>
                    <th>დგომის<br>ფაქტიური<br>დრო</th>
                    <th>'დაკარგული<br>დრო'</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php
                foreach ($routes as $route) {

                    $days = $route->getDays();
                    echo "<tr><td colspan='13'><center>მარშრუტა #: " . $route->getNumber() . "</center></td></tr>";


                    foreach ($days as $day) {
                        echo "<tr><td colspan='13'><center>თარიღი: " . $day->getDateStamp() . "</center></td></tr>";
                        $exoduses = $day->getExoduses();
                        foreach ($exoduses as $exodus) {
                            echo "<tr><td colspan='13'><center>გასვლია #: " . $exodus->getNumber() . "<center></td></tr>";

                            $tripVouchers = $exodus->getTripVouchers();
                            foreach ($tripVouchers as $tripVoucher) {
                                echo "<tr><td colspan='13'><center>მარშრუტა #" . $route->getNumber()
                                . ". თარიღი:" . $day->getDateStamp()
                                . ". გასვლია #" . $exodus->getNumber()
                                . ". საგზური #" . $tripVoucher->getNumber()
                                // . " Bus Type: " . $tripVoucher->getBusType()
                                //. " Bus Number: " . $tripVoucher->getBusNumber()
                                //. "/// Driver Number: " . $tripVoucher->getDriverNumber()
                                //. "/// Driver Name: " . $tripVoucher->getDriverName()
                                . ". შენიშვნები: " . $tripVoucher->getNotes() . "</center></td></tr>";

                                $tripPeriods = $tripVoucher->getTripPeriods();
                                foreach ($tripPeriods as $tripPeriod) {

                                    echo "<tr>"
                                    . "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                    . "<td>" . $tripPeriod->getStartTimeActual() . "</td>"
                                    . "<td>" . $tripPeriod->getStartTimeDifference() . "</td>"
                                    . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                    . "<td>" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                    . "<td></td>"
                                    . "<td>" . $tripPeriod->getTripPeriodScheduledTime() . "</td>"
                                    . "<td>" . $tripPeriod->getTripPeriodActualTime() . "</td>"
                                    . "<td>" . $tripPeriod->getType() . "</td>"
                                    . "<td>" . $tripPeriod->getType() . "</td>"
                                    . "<td>" . $tripPeriod->getType() . "</td>"
                                    . "</tr>";
                                }
                            }
                        }
                    }
                }
                ?>
            </tbody>

        </table>

    </body>
</html>
