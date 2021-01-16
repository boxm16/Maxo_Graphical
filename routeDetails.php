<?php
require_once 'Controller/RouteXLController.php';

$routeController = new RouteXLController();
$routesDetailedPackage = $routeController->getRoutesDelailedPackage();
$routes = $routesDetailedPackage["routes"];
$startTimeActualPackage = $routesDetailedPackage["startTimeActualPackage"];
$startTimeScheduledPackage = $routesDetailedPackage["startTimeScheduledPackage"];
$startTimeDifferencePackage = $routesDetailedPackage["startTimeDifferencePackage"];
$tripPeriodTypePackage = $routesDetailedPackage["tripPeriodTypePackage"];
$arrivalTimeScheduledPackage = $routesDetailedPackage["arrivalTimeScheduledPackage"];
$arrivalTimeActualPackage = $routesDetailedPackage["arrivalTimeActualPackage"];
$arrivalTimeDifferencePackage = $routesDetailedPackage["arrivalTimeDifferencePackage"];
$tripPeriodScheduledPackage = $routesDetailedPackage["tripPeriodScheduledPackage"];
$tripPeriodActualPackage = $routesDetailedPackage["tripPeriodActualPackage"];
$haltTimeScheduledPackage = $routesDetailedPackage["haltTimeScheduledPackage"];
$haltTimeActualPackage = $routesDetailedPackage["haltTimeActualPackage"];
$lostTimePackage = $routesDetailedPackage["lostTimePackage"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ბრუნები დეტალურად</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
            /* navbar styling */
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background-color: green;
                position: fixed;
                top: 0;
                width: 2500px;
            }

            li {
                float: left;
            }

            li a {
                display: block;
                color: white;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
            }
            li button {

                color: white;
                text-align: center;
                padding: 11px ;
                text-decoration: none;
            }

            li a:hover {
                background-color:white;
            }

            .active {
                background-color: lightgreen;
            }
            /* end of navbar styling */
            /* loader styling */
            .content {display:none;}
            .preload { width:100px;
                       height: 100px;
                       position: fixed;
                       top: 50%;
                       left: 50%;}
            /* end of loader styling*/



            /* modal window */

            .modal-dialog {
                max-width: 100%;
                margin: 2rem auto;
            } /* Standard Tables */

            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }

            th {
                vertical-align: bottom;
                background-color: #666;
                color: #fff;
            }



            /* Fixed Headers */

            th {
                position: -webkit-sticky;
                position: sticky;
                top: 45px;
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
        <?php
        include 'navBar.php';
        ?>
        <div class="preload1"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content1">
            <table  id="mainTable" style="width:100%">
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

                <tbody id="mainTableBody">
                    <?php
                    foreach ($routes as $route) {

                        $days = $route->getDays();
                        echo "<tr><td colspan='13'><center>მარშრუტა #: " . $route->getNumber() . "</center></td></tr>";


                        foreach ($days as $day) {
                            echo "<tr><td colspan='13'><center>თარიღი: " . $day->getDateStamp() . "</center></td></tr>";
                            $exoduses = $day->getExoduses();
                            foreach ($exoduses as $exodus) {
                                echo "<tr><td colspan='13'><center>გასვლა #: " . $exodus->getNumber() . "<center></td></tr>";


                                $tripVouchers = $exodus->getTripVouchers();
                                foreach ($tripVouchers as $tripVoucher) {
                                    echo "<tr><td colspan='13'><center>მარშრუტი #" . $route->getNumber()
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
                                        $lostTimeLights = $tripPeriod->getLightsForLostTime();
                                        $rowColor = "white";
                                        if ($tripPeriod->getType() == "break") {
                                            $rowColor = "lightgrey";
                                        }
                                        echo "<tr style=\"background-color:$rowColor;\">"
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
                                        . "<td>" . $tripPeriod->getHaltTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getHaltTimeActual() . "</td>"
                                        . "<td style='background-color:$lostTimeLights'>" . $tripPeriod->getLostTime() . "</td>"
                                        . "</tr>";
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <!--MODAL WINODW start -->
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ფილტრები</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table style="width:100%;"  height="100px">
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
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>

                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>

                                    <td><input type="checkbox"></td>

                                    <td></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>

                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($startTimeScheduledPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"startTimeScheduledPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($startTimeActualPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"startTimeActualPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($startTimeDifferencePackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"startTimeDifferencePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td> 
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($tripPeriodTypePackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"tripPeriodTypePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($arrivalTimeScheduledPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"arrivalTimeScheduledPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($arrivalTimeActualPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"arrivalTimeActualPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($arrivalTimeDifferencePackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"arrivalTimeDifferencePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td></td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($tripPeriodScheduledPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"tripPeriodScheduledPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($tripPeriodActualPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"tripPeriodActualPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($haltTimeScheduledPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"haltTimeScheduledPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($haltTimeActualPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"haltTimeActualPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($lostTimePackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"lostTimePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x </td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="filter()">გაფილტრვა</button>
                        </div>

                    </div>
                </div>
            </div>
            <!--MODAL WINODW end -->

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
            <script>
                                //this founction is for loader spinner. alsow first scrip srs is for this spinner, whout older does not work
                                $(function () {
                                    $(".preload").fadeOut(2000, function () {
                                        $(".content").fadeIn(1000);
                                    });
                                });
                                //this code is for adding row clicking listener
                                var chosenRow = null
                                var cells = document.querySelectorAll("tr");
                                for (var cell of cells) {
                                    cell.addEventListener('click', marker)
                                }

                                function marker(event) {
                                    var row = event.target.parentNode;
                                    if (chosenRow != null) {
                                        chosenRow.style.fontWeight = "normal";
                                    }
                                    row.style.fontWeight = "bold";
                                    chosenRow = row;
                                }

//this is for filtering--------------------
                                var rows = document.getElementById("mainTable").rows;
                                var cloneRows = new Array();
                                for (y = 0; y < rows.length; y++) {
                                    var cloneRow = rows[y];
                                    cloneRows.push(cloneRow);
                                }

                                function filter() {

                                    var startTimeScheduledCheckboxes = document.querySelectorAll('input[name=startTimeScheduledPackage]:checked');
                                    var startTimeActualCheckboxes = document.querySelectorAll('input[name=startTimeActualPackage]:checked');
                                    var startTimeDifferenceCheckboxes = document.querySelectorAll('input[name=startTimeDifferencePackage]:checked');
                                    var tripPeriodTypeCheckboxes = document.querySelectorAll('input[name=tripPeriodTypePackage]:checked');
                                    var arrivalTimeScheduledCheckboxes = document.querySelectorAll('input[name=arrivalTimeScheduledPackage]:checked');
                                    var arrivalTimeActualCheckboxes = document.querySelectorAll('input[name=arrivalTimeActualPackage]:checked');
                                    var arrivalTimeDifferenceCheckboxes = document.querySelectorAll('input[name=arrivalTimeDifferencePackage]:checked');
                                    var tripPeriodScheduledCheckboxes = document.querySelectorAll('input[name=tripPeriodScheduledPackage]:checked');
                                    var tripPeriodActualCheckboxes = document.querySelectorAll('input[name=tripPeriodActualPackage]:checked');
                                    var haltTimeScheduledCheckboxes = document.querySelectorAll('input[name=haltTimeScheduledPackage]:checked');
                                    var haltTimeActualCheckboxes = document.querySelectorAll('input[name=haltTimeActualPackage]:checked');
                                    var lostTimeCheckboxes = document.querySelectorAll('input[name=lostTimePackage]:checked');
                                    var startTimeScheduledArray = new Array();
                                    var startTimeActualArray = new Array();
                                    var startTimeDifferenceArray = new Array();
                                    var tripPeriodTypeArray = new Array();
                                    var arrivalTimeScheduledArray = new Array();
                                    var arrivalTimeActualArray = new Array();
                                    var arrivalTimeDifferenceArray = new Array();
                                    var tripPeriodScheduledArray = new Array();
                                    var tripPeriodActualArray = new Array();
                                    var haltTimeScheduledArray = new Array();
                                    var haltTimeActualArray = new Array();
                                    var lostTimeArray = new Array();
                                    for (x = 0; x < startTimeScheduledCheckboxes.length; x++) {
                                        startTimeScheduledArray.push(startTimeScheduledCheckboxes[x].value)
                                    }
                                    for (x = 0; x < startTimeActualCheckboxes.length; x++) {
                                        startTimeActualArray.push(startTimeActualCheckboxes[x].value)
                                    }
                                    for (x = 0; x < startTimeDifferenceCheckboxes.length; x++) {
                                        startTimeDifferenceArray.push(startTimeDifferenceCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodTypeCheckboxes.length; x++) {
                                        tripPeriodTypeArray.push(tripPeriodTypeCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeScheduledCheckboxes.length; x++) {
                                        arrivalTimeScheduledArray.push(arrivalTimeScheduledCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeActualCheckboxes.length; x++) {
                                        arrivalTimeActualArray.push(arrivalTimeActualCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeDifferenceCheckboxes.length; x++) {
                                        arrivalTimeDifferenceArray.push(arrivalTimeDifferenceCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodScheduledCheckboxes.length; x++) {
                                        tripPeriodScheduledArray.push(tripPeriodScheduledCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodActualCheckboxes.length; x++) {
                                        tripPeriodActualArray.push(tripPeriodActualCheckboxes[x].value)
                                    }
                                    for (x = 0; x < haltTimeScheduledCheckboxes.length; x++) {
                                        haltTimeScheduledArray.push(haltTimeScheduledCheckboxes[x].value)
                                    }
                                    for (x = 0; x < haltTimeActualCheckboxes.length; x++) {
                                        haltTimeActualArray.push(haltTimeActualCheckboxes[x].value)
                                    }
                                    for (x = 0; x < lostTimeCheckboxes.length; x++) {
                                        lostTimeArray.push(lostTimeCheckboxes[x].value)
                                    }



                                    mainTableBody.innerHTML = "";
                                    for (x = 0; x < cloneRows.length; x++) {
                                        var cells = cloneRows[x].getElementsByTagName("td");
                                        if (cells.length <= 1) {
                                            mainTableBody.appendChild(cloneRows[x]);
                                        } else {
                                            if (startTimeScheduledArray.includes(cells[0].innerHTML)
                                                    && startTimeActualArray.includes(cells[1].innerHTML)
                                                    && startTimeDifferenceArray.includes(cells[2].innerHTML)
                                                    && tripPeriodTypeArray.includes(cells[3].innerHTML)
                                                    && arrivalTimeScheduledArray.includes(cells[4].innerHTML)
                                                    && arrivalTimeActualArray.includes(cells[5].innerHTML)
                                                    && arrivalTimeDifferenceArray.includes(cells[6].innerHTML)
                                                    && tripPeriodScheduledArray.includes(cells[8].innerHTML)
                                                    && tripPeriodActualArray.includes(cells[9].innerHTML)
                                                    && haltTimeScheduledArray.includes(cells[10].innerHTML)
                                                    && haltTimeActualArray.includes(cells[11].innerHTML)
                                                    && lostTimeArray.includes(cells[12].innerHTML)) {

                                                mainTableBody.appendChild(cloneRows[x]);
                                            }
                                        }
                                    }
                                }

            </script>
    </body>
</html>
