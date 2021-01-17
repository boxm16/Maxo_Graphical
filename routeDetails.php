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
            }
            /* Standard Tables */

            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }

            th {
                vertical-align: bottom;
                background-color: blue;
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
        <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content">
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
                        <th>link</th>
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
                        $routeNumber = $route->getNumber();
                        $days = $route->getDays();
                        echo "<tr><td colspan='13'><center>მარშრუტა #: " . $routeNumber . "</center></td></tr>";


                        foreach ($days as $day) {
                            $dateStamp = $day->getDateStamp();
                            echo "<tr><td colspan='13'><center>თარიღი: " . $dateStamp . "</center></td></tr>";
                            $exoduses = $day->getExoduses();
                            foreach ($exoduses as $exodus) {
                                $exodusNumber = $exodus->getNumber();
                                echo "<tr><td colspan='13'><center>გასვლა #: " . $exodusNumber . "<center></td></tr>";


                                $tripVouchers = $exodus->getTripVouchers();
                                foreach ($tripVouchers as $tripVoucher) {
                                    echo "<tr><td colspan='13'><center>მარშრუტი #" . $route->getNumber()
                                    . ". თარიღი:" . $day->getDateStamp()
                                    . ". გასვლა #" . $exodus->getNumber()
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
                                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                        echo "<tr style=\"background-color:$rowColor;\">"
                                        . "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getStartTimeActual() . "</td>"
                                        . "<td>" . $tripPeriod->getStartTimeDifference() . "</td>"
                                        . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                        . "<td><a href='exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled'  target='_blank'>link</a></td>"
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

        <!-- FILTER MODAL WINODW start -->
        <!-- Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ფილტრები</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="modalTable" style="width:100%;"  height="100px">
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
                                    <td><input type="checkbox" onclick="check(event, 0)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 1)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 2)" checked="true"> ყველა</td>

                                    <td><input type="checkbox" onclick="check(event, 3)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 4)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 5)" checked="true"> ყველა</td>

                                    <td><input type="checkbox" onclick="check(event, 6)" checked="true"> ყველა</td>

                                    <td></td>
                                    <td><input type="checkbox" onclick="check(event, 8)" checked="true"> ყველა</td>

                                    <td><input type="checkbox" onclick="check(event, 9)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 10)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 11)" checked="true"> ყველა</td>
                                    <td><input type="checkbox" onclick="check(event, 12)" checked="true"> ყველა</td>

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
                            <input name="captures" id="captures" type="checkbox" checked="true"  align="center"> სათაურები
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="filter()">გაფილტრვა</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--FILTER MODAL WINODW end -->

        <!--MARKER MODAL WINODW start -->
        <!-- Modal -->
        <div class="modal fade" id="markerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">მარკერები</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="modalTable" style="width:100%;"  height="100px">
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
                                    <td>
                                        <table width="100%">
                                            <thead stlyle="display:block;" ></thead>
                                            <tbody style="height:300px; overflow-y:scroll; display:block;">
                                                <?php
                                                foreach ($startTimeScheduledPackage as $x => $x_value) {
                                                    echo "<tr><td><input name=\"startTimeScheduledMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"startTimeActualMarker\" type=\"checkbox\"  value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"startTimeDifferenceMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"tripPeriodTypeMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"arrivalTimeScheduledMarker\" type=\"checkbox\"  value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"arrivalTimeActualMarker\" type=\"checkbox\"  value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"arrivalTimeDifferenceMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"tripPeriodScheduledMarker\" type=\"checkbox\"  value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"tripPeriodActualMarker\" type=\"checkbox\"  value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"haltTimeScheduledMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    echo "<tr><td><input name=\"haltTimeActualMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    //  echo "<tr><td><input name=\"lostTimeMarker\" type=\"checkbox\" value=\"$x\"></td><td>$x </td></tr>";
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="mark()">მონიშვნა</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--MARKER MODAL WINODW end -->

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
                                var cells = document.getElementById("mainTable").querySelectorAll("tr");
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
                                    var captures = document.getElementById("captures");
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
                                        if (x == 0) {
                                            mainTableBody.appendChild(cloneRows[x]);//table headers
                                        }
                                        if (cells.length <= 1) {
                                            if (captures.checked) {
                                                mainTableBody.appendChild(cloneRows[x]);
                                            }
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

                                function mark() {
                                    var startTimeScheduledMarkerCheckboxes = document.querySelectorAll('input[name=startTimeScheduledMarker]:checked');
                                    var startTimeActualMarkerCheckboxes = document.querySelectorAll('input[name=startTimeActualMarker]:checked');
                                    var startTimeDifferenceMarkerCheckboxes = document.querySelectorAll('input[name=startTimeDifferenceMarker]:checked');
                                    var tripPeriodTypeMarkerCheckboxes = document.querySelectorAll('input[name=tripPeriodTypeMarker]:checked');
                                    var arrivalTimeScheduledMarkerCheckboxes = document.querySelectorAll('input[name=arrivalTimeScheduledMarker]:checked');
                                    var arrivalTimeActualMarkerCheckboxes = document.querySelectorAll('input[name=arrivalTimeActualMarker]:checked');
                                    var arrivalTimeDifferenceMarkerCheckboxes = document.querySelectorAll('input[name=arrivalTimeDifferenceMarker]:checked');
                                    var tripPeriodScheduledMarkerCheckboxes = document.querySelectorAll('input[name=tripPeriodScheduledMarker]:checked');
                                    var tripPeriodActualMarkerCheckboxes = document.querySelectorAll('input[name=tripPeriodActualMarker]:checked');
                                    var haltTimeScheduledMarkerCheckboxes = document.querySelectorAll('input[name=haltTimeScheduledMarker]:checked');
                                    var haltTimeActualMarkerCheckboxes = document.querySelectorAll('input[name=haltTimeActualMarker]:checked');
                                    var lostTimeMarkerCheckboxes = document.querySelectorAll('input[name=lostTimeMarker]:checked');
                                    var startTimeScheduledMarkerArray = new Array();
                                    var startTimeActualMarkerArray = new Array();
                                    var startTimeDifferenceMarkerArray = new Array();
                                    var tripPeriodTypeMarkerArray = new Array();
                                    var arrivalTimeScheduledMarkerArray = new Array();
                                    var arrivalTimeActualMarkerArray = new Array();
                                    var arrivalTimeDifferenceMarkerArray = new Array();
                                    var tripPeriodScheduledMarkerArray = new Array();
                                    var tripPeriodActualMarkerArray = new Array();
                                    var haltTimeScheduledMarkerArray = new Array();
                                    var haltTimeActualMarkerArray = new Array();
                                    var lostTimeMarkerArray = new Array();
                                    for (x = 0; x < startTimeScheduledMarkerCheckboxes.length; x++) {
                                        startTimeScheduledMarkerArray.push(startTimeScheduledMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < startTimeActualMarkerCheckboxes.length; x++) {
                                        startTimeActualMarkerArray.push(startTimeActualMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < startTimeDifferenceMarkerCheckboxes.length; x++) {
                                        startTimeDifferenceMarkerArray.push(startTimeDifferenceMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodTypeMarkerCheckboxes.length; x++) {
                                        tripPeriodTypeMarkerArray.push(tripPeriodTypeMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeScheduledMarkerCheckboxes.length; x++) {
                                        arrivalTimeScheduledMarkerArray.push(arrivalTimeScheduledMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeActualMarkerCheckboxes.length; x++) {
                                        arrivalTimeActualMarkerArray.push(arrivalTimeActualMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeDifferenceMarkerCheckboxes.length; x++) {
                                        arrivalTimeDifferenceMarkerArray.push(arrivalTimeDifferenceMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodScheduledMarkerCheckboxes.length; x++) {
                                        tripPeriodScheduledMarkerArray.push(tripPeriodScheduledMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodActualMarkerCheckboxes.length; x++) {
                                        tripPeriodActualMarkerArray.push(tripPeriodActualMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < haltTimeScheduledMarkerCheckboxes.length; x++) {
                                        haltTimeScheduledMarkerArray.push(haltTimeScheduledMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < haltTimeActualMarkerCheckboxes.length; x++) {
                                        haltTimeActualMarkerArray.push(haltTimeActualMarkerCheckboxes[x].value)
                                    }
                                    for (x = 0; x < lostTimeMarkerCheckboxes.length; x++) {
                                        lostTimeMarkerArray.push(lostTimeMarkerCheckboxes[x].value)
                                    }


                                    var markRows = document.getElementById("mainTable").rows;
                                    for (a = 0; a < markRows.length; a++) {

                                        var cells = markRows[a].getElementsByTagName("td");
                                        if (cells.length > 1) {

                                            if (startTimeScheduledMarkerArray.includes(cells[0].innerHTML)) {
                                                cells[0].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[0].style.backgroundColor = "white";
                                            }

                                            if (startTimeActualMarkerArray.includes(cells[1].innerHTML)) {
                                                cells[1].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[1].style.backgroundColor = "white";
                                            }
                                            if (startTimeDifferenceMarkerArray.includes(cells[2].innerHTML)) {
                                                cells[2].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[2].style.backgroundColor = "white";
                                            }
                                            if (tripPeriodTypeMarkerArray.includes(cells[3].innerHTML)) {
                                                cells[3].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[3].style.backgroundColor = "white";
                                            }
                                            if (arrivalTimeScheduledMarkerArray.includes(cells[4].innerHTML)) {
                                                cells[4].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[4].style.backgroundColor = "white";
                                            }
                                            if (arrivalTimeActualMarkerArray.includes(cells[5].innerHTML)) {
                                                cells[5].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[5].style.backgroundColor = "white";
                                            }
                                            if (arrivalTimeDifferenceMarkerArray.includes(cells[6].innerHTML)) {
                                                cells[6].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[6].style.backgroundColor = "white";
                                            }
                                            if (tripPeriodScheduledMarkerArray.includes(cells[8].innerHTML)) {
                                                cells[8].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[8].style.backgroundColor = "white";
                                            }
                                            if (tripPeriodActualMarkerArray.includes(cells[9].innerHTML)) {
                                                cells[9].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[9].style.backgroundColor = "white";
                                            }
                                            if (haltTimeScheduledMarkerArray.includes(cells[10].innerHTML)) {
                                                cells[10].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[10].style.backgroundColor = "white";
                                            }
                                            if (haltTimeActualMarkerArray.includes(cells[11].innerHTML)) {
                                                cells[11].style.backgroundColor = "lightgreen";
                                            } else {
                                                cells[11].style.backgroundColor = "white";
                                            }
                                            if (lostTimeMarkerArray.includes(cells[12].innerHTML)) {
                                                cells[12].style.backgroundColor = "lightgreen";
                                            } else {
                                                // cells[12].style.backgroundColor = "white";
                                            }



                                        }

                                    }


                                }

                                //  end for marker --------

                                //now for checking all checkboxes of a column

                                function check(event, rowNumber) {//too complicated, you can make it much more elegant
                                    var checkboxes;
                                    switch (rowNumber)
                                    {
                                        case 0:
                                            checkboxes = document.querySelectorAll('input[name=startTimeScheduledPackage]');
                                            break;
                                        case 1:
                                            checkboxes = document.querySelectorAll('input[name=startTimeActualPackage]');
                                            break;
                                        case 2:
                                            checkboxes = document.querySelectorAll('input[name=startTimeDifferencePackage]');
                                            break;
                                        case 3:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodTypePackage]');
                                            break;
                                        case 4:
                                            checkboxes = document.querySelectorAll('input[name=arrivalTimeScheduledPackage]');
                                            break;
                                        case 5:
                                            checkboxes = document.querySelectorAll('input[name=arrivalTimeActualPackage]');
                                            break;
                                        case 6:
                                            checkboxes = document.querySelectorAll('input[name=arrivalTimeDifferencePackage]');
                                            break;
                                        case 8:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodScheduledPackage]');
                                            break;
                                        case 9:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodActualPackage]');
                                            break;
                                        case 10:
                                            checkboxes = document.querySelectorAll('input[name=haltTimeScheduledPackage]');
                                            break;
                                        case 11:
                                            checkboxes = document.querySelectorAll('input[name=haltTimeActualPackage]');
                                            break;
                                        case 12:
                                            checkboxes = document.querySelectorAll('input[name=lostTimePackage]');
                                            break;
                                    }
                                    console.log(event.target.checked);
                                    for (x = 0; x < checkboxes.length; x++) {
                                        checkboxes[x].checked = event.target.checked;
                                    }
                                }

                                //this function for copng table in clipboard
                                function copytable(el) {
                                    var urlField = document.getElementById(el)
                                    var range = document.createRange()
                                    range.selectNode(urlField)
                                    window.getSelection().addRange(range)
                                    document.execCommand('copy')
                                }

        </script>
    </body>
</html>
