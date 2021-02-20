<?php
require_once './Controller/RouteXLController.php';
$bodyBuilder = "";
if (isset($_GET["routeNumber"]) && isset($_GET["dateStamp"]) && isset($_GET["exodusNumber"]) && isset($_GET["startTimeScheduled"])) {
    $routeNumber = $_GET["routeNumber"];
    $dateStamp = $_GET["dateStamp"];
    $exodusNumber = $_GET["exodusNumber"];
    $startTimeScheduled = $_GET["startTimeScheduled"];
    $exodusDetails = "$dateStamp,  მარშრუტი # $routeNumber, გასვლა #$exodusNumber";
    $routeController = new RouteXLController();
    $routes = $routeController->getFullRoutes();

    $found = false;

    foreach ($routes as $route) {
        $routeNumberFromData = $route->getNumber();
        if ($routeNumber == $routeNumberFromData) {
            $days = $route->getDays();

            foreach ($days as $day) {
                $dateStampFromData = $day->getDateStamp();
                $day->getIntervals(); //here I actially set Intervals

                if ($dateStamp == $dateStampFromData) {
                    $exoduses = $day->getExoduses();
                    foreach ($exoduses as $exodus) {
                        $exodusNumberFromData = $exodus->getNumber();
                        if ($exodusNumber == $exodusNumberFromData) {
                            $tripVouchers = $exodus->getTripVouchers();
                            $bodyBuilder = "";
                            foreach ($tripVouchers as $tripVoucher) {
                                $tripVoucherNumber = $tripVoucher->getNumber();
                                $notes = $tripVoucher->getNotes();
                                $voucherRow = "<tr><td colspan=\"13\">საგზური#: $tripVoucherNumber. შენიშვნები:$notes</td></tr>";
                                $bodyBuilder .= $voucherRow;
                                $tripPeriods = $tripVoucher->getTripPeriods();

                                foreach ($tripPeriods as $tripPeriod) {
                                    $found = true;


                                    $lostTimeLights = $tripPeriod->getLightsForLostTime();
                                    $startTimeDifferenceLights = $tripPeriod->getStartTimeDifferenceColor();
                                    $arrivalTimeDifferenceLights = $tripPeriod->getArrivalTimeDifferenceColor();

                                    $rowColor = "white";
                                    if ($tripPeriod->getType() == "break") {
                                        $rowColor = "lightgrey";
                                        if ($startTimeDifferenceLights == "white") {
                                            $startTimeDifferenceLights = "lightgrey";
                                        }
                                        if ($arrivalTimeDifferenceLights == "white") {
                                            $arrivalTimeDifferenceLights = "lightgrey";
                                        }
                                    }
                                    $startTimeScheduledFromData = $tripPeriod->getStartTimeScheduled();
                                    if ($startTimeScheduled == $startTimeScheduledFromData) {
                                        $rowColor = "lightgreen";
                                    }
                                    $tripPeriodDifferenceTimeLights = $tripPeriod->getTripPeriodDifferenceTimeColor();
                                    $tripPeriodType = $tripPeriod->getType();
                                    $tripPeriodRow = "<tr style=\"background-color:$rowColor;\">"
                                            . "<td name='startTimeScheduled'>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                            . "<td name='startTimeActual'>" . $tripPeriod->getStartTimeActual() . "</td>"
                                            . "<td name='startTimeDifference' style=\"background-color:$startTimeDifferenceLights;\">" . $tripPeriod->getStartTimeDifference() . "</td>"
                                            . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                            . "<td name='arrivalTimeScheduled'>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                            . "<td name='arrivalTimeActual'>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                            . "<td name='startTimeDifference' style=\"background-color:$arrivalTimeDifferenceLights;\">" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                            . "<td><a href='exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled'  target='_blank'>link</a></td>"
                                            . "<td name='tripPeriodScheduledTime'>" . $tripPeriod->getTripPeriodScheduledTime() . "</td>"
                                            . "<td name='tripPeriodActualTime'>" . $tripPeriod->getTripPeriodActualTime() . "</td>"
                                            . "<td name='tripPeriodDifferenceTime' style=\"background-color:$tripPeriodDifferenceTimeLights;\" >" . $tripPeriod->getTripPeriodDifferenceTime() . "</td>"
                                            . "<td name='haltTimeScheduled'>" . $tripPeriod->getHaltTimeScheduled() . "</td>"
                                            . "<td name='haltTimeActual'>" . $tripPeriod->getHaltTimeActual() . "</td>"
                                            . "<td name='lostTime' style='background-color:$lostTimeLights'>" . $tripPeriod->getLostTime() . "</td>"
                                            . "<td style='background-color:white'> " . $tripPeriod->getGpsBasedActualInterval() . " <a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$startTimeScheduled'  target='_blank'>   O</a></td>"
                                            . "</tr>";

                                    $bodyBuilder .= $tripPeriodRow;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    $exodusDetails = "რაღაც შეცდომა მოხდა, სცადე თავიდან";
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>

            /* for stickign */

            /* Standard Tables */

            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }


            th {
                vertical-align: bottom;
                background-color: white;
                color: black;
            }


            /* Fixed Headers */

            th {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                z-index: 2;

            }

            th[scope=row] {
                position: -webkit-sticky;
                position: sticky;
                left: 0;
                z-index: 1;
            }

            th[scope=row] {
                vertical-align: top;
                color: inherit;
                background-color: inherit;
                background: linear-gradient(90deg, transparent 0%, transparent calc(100% - .05em), #d6d6d6 calc(100% - .05em), #d6d6d6 100%);
            }


        </style>
    </head>
    <body>
        <div style="background-color:green;color:white"> <center><h2> <?php echo $exodusDetails; ?> </h2></center></div>

        <table style="width:100%">
            <thead>

                <tr>
                    <th>გეგმიუირი<br>გასვლის<br>დრო</th>
                    <th>ფაქტიური<br>გასვლის<br>დრო</th>
                    <th>სხვაობა</th>
                    <th>მიმართულება</th>
                    <th>გეგმიუირი<br>მისვლის<br>დრო</th>
                    <th>ფაქტიური<br>მისვლის<br>დრო</th>
                    <th>სხვაობა</th>
                    <th></th>
                    <th>წირის<br>გეგმიური<br>დრო</th>
                    <th>წირის<br>ფაქტიური<br>დრო</th>
                    <th>სხვაობა</th>
                    <th>დგომის<br>გეგმიური<br> დრო</th>
                    <th>დგომის<br>ფაქტიური<br>დრო</th>
                    <th>'დაკარგული<br>დრო'</th>
                    <th>GPS ინტერვალი</th>
                </tr>
            </thead>
            <tbody> 
                <?php
                echo $bodyBuilder;
                ?>
            </tbody>
        </table>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            //this code is for adding row clicking listener
            var chosenRow = null
            var cells = document.querySelectorAll("tr");

            for (var cell of cells) {
                cell.addEventListener('click', marker);
                cell.addEventListener('dblclick', markCells);
            }

            function marker(event) {
                var row = event.target.parentNode;
                if (chosenRow != null) {
                    chosenRow.style.fontWeight = "normal";
                }
                row.style.fontWeight = "bold";
                chosenRow = row;
            }

            //-------------------
            var previousCells = new Array();
            function markCells(event) {
                if (previousCells.length > 0) {
                    for (let x = 0; x < previousCells.length; x++) {
                        var loc = previousCells[x];
                        var el = loc.element;
                        el.style.backgroundColor = loc.originalColor;
                    }
                }
                var targetCell = event.target;
                var cellName = targetCell.getAttribute('name');
                if (cellName == "tripPeriodScheduledTime") {
                    var targetRow = event.target.parentNode;
                    var cellOne = targetRow.querySelector("td[name=startTimeScheduled]");
                    var cellTwo = targetRow.querySelector("td[name=arrivalTimeScheduled");

                    saveElementColor(targetCell, cellOne, cellTwo);

                    targetCell.style.backgroundColor = "violet";
                    cellOne.style.backgroundColor = "violet";
                    cellTwo.style.backgroundColor = "violet";


                }
                if (cellName == "tripPeriodActualTime") {
                    var targetRow = event.target.parentNode;
                    var cellOne = targetRow.querySelector("td[name=startTimeActual]");
                    var cellTwo = targetRow.querySelector("td[name=arrivalTimeActual");

                    saveElementColor(targetCell, cellOne, cellTwo);

                    targetCell.style.backgroundColor = "violet";
                    cellOne.style.backgroundColor = "violet";
                    cellTwo.style.backgroundColor = "violet";
                }
                if (cellName == "tripPeriodDifferenceTime") {
                    var targetRow = event.target.parentNode;
                    var cellOne = targetRow.querySelector("td[name=tripPeriodScheduledTime]");
                    var cellTwo = targetRow.querySelector("td[name=tripPeriodActualTime");

                    saveElementColor(targetCell, cellOne, cellTwo);

                    targetCell.style.backgroundColor = "violet";
                    cellOne.style.backgroundColor = "violet";
                    cellTwo.style.backgroundColor = "violet";
                }


                if (cellName == "haltTimeScheduled") {

                    var targetRow = event.target.parentNode;
                    var previousRow = targetRow.previousSibling;
                    var cellOne = targetRow.querySelector("td[name=startTimeScheduled]");
                    var cellTwo = previousRow.querySelector("td[name=arrivalTimeScheduled");
                    if (cellTwo != null) {

                        saveElementColor(targetCell, cellOne, cellTwo);

                        targetCell.style.backgroundColor = "violet";
                        cellOne.style.backgroundColor = "violet";
                        cellTwo.style.backgroundColor = "violet";
                    }
                }
                if (cellName == "haltTimeActual") {
                    var targetRow = event.target.parentNode;
                    var previousRow = targetRow.previousSibling;
                    var cellOne = targetRow.querySelector("td[name=startTimeActual]");
                    var cellTwo = previousRow.querySelector("td[name=arrivalTimeActual");
                    if (cellTwo != null) {

                        saveElementColor(targetCell, cellOne, cellTwo);

                        targetCell.style.backgroundColor = "violet";
                        cellOne.style.backgroundColor = "violet";
                        cellTwo.style.backgroundColor = "violet";
                    }
                }

                if (cellName == "lostTime") {
                    var targetRow = event.target.parentNode;

                    var cellOne = targetRow.querySelector("td[name=startTimeDifference]");
                    var cellTwo = targetRow.querySelector("td[name=haltTimeActual");
                    if (cellTwo != null) {

                        saveElementColor(targetCell, cellOne, cellTwo);

                        if (targetCell.innerText == cellOne.innerText) {
                            cellOne.style.backgroundColor = "violet";
                        }
                        if (targetCell.innerText == cellTwo.innerText) {
                            cellTwo.style.backgroundColor = "violet";
                        }
                        targetCell.style.backgroundColor = "violet";

                    }
                }
            }


            function saveElementColor(targetCell, cellOne, cellTwo) {
                var loc_0 = {element: targetCell, originalColor: targetCell.style.backgroundColor};
                var loc_1 = {element: cellOne, originalColor: cellOne.style.backgroundColor};
                var loc_2 = {element: cellTwo, originalColor: cellTwo.style.backgroundColor};


                previousCells.push(loc_0);
                previousCells.push(loc_1);
                previousCells.push(loc_2);
            }
            //------------------------------------------------------

            function markRow(event) {
                var row = event.target.parentNode;
                if (chosenRow != null) {
                    chosenRow.style.fontWeight = "normal";
                }
                row.style.fontWeight = "bold";
                chosenRow = row;
            }

        </script>
    </body>
</html>
