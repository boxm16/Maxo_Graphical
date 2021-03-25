<?php
require_once 'Controller/RouteXLController.php';
require_once 'clientId.php';
session_start();
if (isset($_POST["routes:dates"])) {
    $_SESSION["routes:dates"] = $_POST["routes:dates"];
    $requestedRoutesAndDates = $_POST["routes:dates"];
} else {
    if (isset($_SESSION["routes:dates"])) {

        $requestedRoutesAndDates = $_SESSION["routes:dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}


$routeController = new RouteXLController();
$excelFormPackage = $routeController->getExcelFormPackage($clientId, $requestedRoutesAndDates);
$routes = $excelFormPackage["routes"];

$routeNumberPackage = $excelFormPackage["routeNumberPackage"];
$dateStampPackage = $excelFormPackage["dateStampPackage"];
$busNumberPackage = $excelFormPackage["busNumberPackage"];
$exodusNumberPackage = $excelFormPackage["exodusNumberPackage"];
$driverNamePackage = $excelFormPackage["driverNamePackage"];
$tripPeriodTypePackage = $excelFormPackage["tripPeriodTypePackage"];
$startTimeActualPackage = $excelFormPackage["startTimeActualPackage"];
$startTimeScheduledPackage = $excelFormPackage["startTimeScheduledPackage"];
$arrivalTimeScheduledPackage = $excelFormPackage["arrivalTimeScheduledPackage"];
$arrivalTimeActualPackage = $excelFormPackage["arrivalTimeActualPackage"];
$tripPeriodScheduledTimePackage = $excelFormPackage["tripPeriodScheduledPackage"];
$tripPeriodActualTimePackage = $excelFormPackage["tripPeriodActualPackage"];
$tripPeriodDifferenceTimePackage = $excelFormPackage["tripPeriodDifferenceTimePackage"];
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ექსელის ფორმა</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
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
                z-index: 3;
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

            li a:hover {
                background-color:white;
            }

            .active {
                background-color: lightgreen;
            }

            /* dropdown button styling start */
            .dropbtn {

                background-color: rgb(207, 110, 45);
                color: white;
                padding:14px 16px;
                font-size: 16px;
                border: none;

            }

            .dropdown {
                position: relative;
                display: inline-block;
            }

            .dropdown-content {
                display: none;
                position: fixed;
                background-color: #f1f1f1;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 4;
            }

            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            .dropdown-content a:hover {background-color: #ddd;}

            .dropdown:hover .dropdown-content {display: block;}

            .dropdown:hover .dropbtn {background-color: white; color:blue;}

            /* dropdown button styling end */


            /* end of navbar styling */




            /* loader styling */
            .content {display:none;}
            .preload { width:100px;
                       height: 100px;
                       position: fixed;
                       top: 50%;
                       left: 50%;}
            /* end of loader styling*/


            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }

            /* modal window */

            .modal-dialog {
                max-width: 100%;
                margin: 2rem auto;
            }


            /* for stickign */

            /* Standard Tables */

            table {

                border-collapse: collapse;
                border: 0.1em solid #d6d6d6;
            }

            th {
                vertical-align: bottom;
                background-color: #D170F7;
                color: #fff;
            }




        </style>
    </head>
    <body>
        <?php
        include 'navBar.php';
        ?>

        <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content"> 


            <!-- FILTER MODAL WINODW start -->
            <!-- Modal -->
            <form id="excelExportForm" action="excelExportDispatcher.php" method="POST"  target="_blank">
                <input name="excelForm" hidden>
                <input id="routeNumber"  name="routeNumber" type="hidden">
                <input id="dateStamp"  name="dateStamp" type="hidden">
                <input id="busNumber"  name="busNumber" type="hidden">
                <input id="exodusNumber"  name="exodusNumber" type="hidden">
                <input id="driverName"  name="driverName" type="hidden">

                <input id="tripPeriodType"  name="tripPeriodType" type="hidden">
                <input id="startTimeScheduled"  name="startTimeScheduled" type="hidden">
                <input id="startTimeActual"  name="startTimeActual" type="hidden">
                <input id="arrivalTimeScheduled"  name="arrivalTimeScheduled" type="hidden">
                <input id="arrivalTimeActual"  name="arrivalTimeActual" type="hidden">

                <input id="tripPeriodScheduled"  name="tripPeriodScheduled" type="hidden">
                <input id="tripPeriodActual"  name="tripPeriodActual" type="hidden">
                <input id="tripPeriodDifference"  name="tripPeriodDifference" type="hidden">
                <input id="percents"  name="percents" type="hidden">
            </form>

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
                                        <th>მარშრუტის #</th>
                                        <th>თარიღი</th>
                                        <th>ავტობუსის #</th>
                                        <th>გასვლის #</th>
                                        <th>მძღოლი</th>
                                        <th>მიმართულება</th>
                                        <th>გასვლის<br>გეგმიური<br>დრო</th>
                                        <th>გასვლის<br>ფაქტიური<br>დრო</th>
                                        <th>მისვლის<br>გეგმიური<br>დრო</th>
                                        <th>მისვლის<br>ფაქტიური<br>დრო</th>
                                        <th>წირის<br>გეგმიური<br>დრო</th>
                                        <th>წირის<br>ფაქტიური<br>დრო</th>
                                        <th>სხვაობა</th>


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
                                        <td><input type="checkbox" onclick="check(event, 7)" checked="true"> ყველა</td>
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
                                                    foreach ($routeNumberPackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"routeNumberPackage\" class=\"routeNumberPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($dateStampPackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"dateStampPackage\" class=\"dateStampPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($busNumberPackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"busNumberPackage\" class=\"busNumberPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($exodusNumberPackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"exodusNumberPackage\" class=\"exodusNumberPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($driverNamePackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"driverNamePackage\" class=\"driverNamePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                        echo "<tr><td><input name=\"tripPeriodTypePackage\" class=\"tripPeriodTypePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($startTimeScheduledPackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"startTimeScheduledPackage\" class=\"startTimeScheduledPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                        echo "<tr><td><input name=\"startTimeActualPackage\" class=\"startTimeActualPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                        echo "<tr><td><input name=\"arrivalTimeScheduledPackage\" class=\"arrivalTimeScheduledPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                        echo "<tr><td><input name=\"arrivalTimeActualPackage\" class=\"arrivalTimeActualPackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($tripPeriodScheduledTimePackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"tripPeriodScheduledTimePackage\" class=\"tripPeriodScheduledTimePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($tripPeriodActualTimePackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"tripPeriodActualTimePackage\" class=\"tripPeriodActualTimePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
                                                    foreach ($tripPeriodDifferenceTimePackage as $x => $x_value) {
                                                        echo "<tr><td><input name=\"tripPeriodDifferenceTimePackage\" class=\"tripPeriodDifferenceTimePackage\" type=\"checkbox\" checked=\"$x_value\" value=\"$x\"></td><td>$x</td></tr>";
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
            </div>

            <!--FILTER MODAL WINODW end -->


            <!-- CALCULATION MODAL WINODW start -->
            <!-- Modal -->
            <div class="modal fade" id="calculationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">გამოთვლები</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="percentDisplay">გამოთვლისთვის არის გამოყენებული მხოლოდ A-B და B-A წირები, რომლების გეგმიური და ფაქტიური დროს შორის აცდენა უდრის ან ნაკლებია( =< ) 20%-ს</p>
                            შეცვალე პროცენტები <input id="percentage" type="number" value="20"><button onclick="calculateAndDisplayAverage()">გამოთვალე თავიდან</button>
                            <table id="calculationModalTable" style="width:100%;"  height="100px">
                                <thead>
                                    <tr>
                                        <th>მარშრუტი</th>
                                        <th>მიმართულება</th>
                                        <th>-პროცენტი<=X<0 ჩათვლილი ბრუნები ჩაოდენობა</th>
                                        <th>-პროცენტი<=X<0 ჩათვლილი ბრუნების საშუალო ფაქტიური დრო</th>
                                        <th>0<=X<პროცენტი ჩათვლილი ბრუნები ჩაოდენობა</th>
                                        <th>0<=X<პროცენტი ჩათვლილი ბრუნების საშუალო ფაქტიური დრო</th>
                                        <th>ყველა ჩათვლილი ბრუნები ჩაოდენობა</th>
                                        <th>ყველა ჩათვლილი ბრუნების საშუალო ფაქტიური დრო</th>
                                        <th>ყველა ჩათვლილი ბრუნების  სტანდარტული გეგმიური დრო</th>
                                        <th>ორივე მიმართულების ბრუნების საშუალო ფაქტიური დრო</th>
                                    </tr>
                                </thead>
                                <tbody id="calculationsTableBody">

                                </tbody>
                            </table>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" data-dismiss="modal" >დახურვა</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--CALCULATION MODAL WINODW end -->


            <table style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align: center">მარშრუტის #</th>
                        <th style="text-align: center">თარიღი</th>
                        <th style="text-align: center">ავტობუსის #</th>
                        <th style="text-align: center">გასვლის #</th>
                        <th style="text-align: center">მძღოლი</th>
                        <th style="text-align: center">მიმართულება</th>
                        <th style="text-align: center">გასვლის<br>გეგმიური<br>დრო</th>
                        <th style="text-align: center">გასვლის<br>ფაქტიური<br>დრო</th>
                        <th style="text-align: center">მისვლის<br>გეგმიური<br>დრო</th>
                        <th style="text-align: center">მისვლის<br>ფაქტიური<br>დრო</th>
                        <th style="text-align: center">წირის<br>გეგმიური<br>დრო</th>
                        <th style="text-align: center">წირის<br>ფაქტიური<br>დრო</th>
                        <th style="text-align: center">სხვაობა</th>
                        <th style="text-align: center">დეტალურად</th>

                    </tr>

                </thead>
                <tbody id="mainTableBody">
                    <?php
                    foreach ($routes as $route) {
                        $days = $route->getDays();

                        foreach ($days as $day) {
                            $exoduses = $day->getExoduses();
                            foreach ($exoduses as $exodus) {
                                $tripVouchers = $exodus->getTripVouchers();
                                foreach ($tripVouchers as $tripVoucher) {
                                    $tripPeriods = $tripVoucher->getTripPeriods();
                                    $firstTripPeriodStartPoint = $tripVoucher->getFirstTripPeriodStartPoint();
                                    $lastTripPeriodEndPoint = $tripVoucher->getLastTripPeriodEndPoint();
                                    foreach ($tripPeriods as $tripPeriod) {
                                        $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
                                        $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
                                        $busNumber = $tripPeriod->getTripPeriodDNA()->getBusNumber();
                                        $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
                                        $driverName = $tripPeriod->getTripPeriodDNA()->getDriverName();
                                        $tripPeriodType = $tripPeriod->getTypeGe();


                                        $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                        $startTimeActual = $tripPeriod->getStartTimeActual();
                                        $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                                        $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                                        $tripPeriodScheduledTime = $tripPeriod->getTripPeriodScheduledTime();
                                        $tripPeriodActualTime = $tripPeriod->getTripPeriodActualTime();
                                        $tripPeriodDifferenceTime = $tripPeriod->getTripPeriodDifferenceTime();
                                        $tripPeriodDifferenceTimeColor = $tripPeriod->getTripPeriodDifferenceTimeColor();
                                        echo "<tr> "
                                        . "<td>$routeNumber</td>"
                                        . "<td>$dateStamp</td>"
                                        . "<td>$busNumber</td>"
                                        . "<td>$exodusNumber</td>"
                                        . "<td>$driverName</td>"
                                        . "<td>$tripPeriodType</td>"
                                        . "<td>$startTimeScheduled</td>"
                                        . "<td>$startTimeActual</td>"
                                        . "<td>$arrivalTimeScheduled</td>"
                                        . "<td>$arrivalTimeActual</td>"
                                        . "<td>$tripPeriodScheduledTime</td>"
                                        . "<td>$tripPeriodActualTime</td>"
                                        . "<td style=\"width:100px;background-color:$tripPeriodDifferenceTimeColor\">$tripPeriodDifferenceTime</td>"
                                        . "<td><a href='exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled'  target='_blank'>დეტალურად</a></td>"
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


                                //now for checking all checkboxes of a column

                                function check(event, rowNumber) {//too complicated, you can make it much more elegant
                                    var checkboxes;
                                    switch (rowNumber)
                                    {
                                        case 0:
                                            checkboxes = document.querySelectorAll('input[name=routeNumberPackage]');
                                            break;
                                        case 1:
                                            checkboxes = document.querySelectorAll('input[name=dateStampPackage]');
                                            break;
                                        case 2:
                                            checkboxes = document.querySelectorAll('input[name=busNumberPackage]');
                                            break;
                                        case 3:
                                            checkboxes = document.querySelectorAll('input[name=exodusNumberPackage]');
                                            break;
                                        case 4:
                                            checkboxes = document.querySelectorAll('input[name=driverNamePackage]');
                                            break;
                                        case 5:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodTypePackage]');
                                            break;
                                        case 6:
                                            checkboxes = document.querySelectorAll('input[name=startTimeScheduledPackage]');
                                            break;
                                        case 7:
                                            checkboxes = document.querySelectorAll('input[name=startTimeActualPackage]');
                                            break;
                                        case 8:
                                            checkboxes = document.querySelectorAll('input[name=arrivalTimeScheduledPackage]');
                                            break;
                                        case 9:
                                            checkboxes = document.querySelectorAll('input[name=arrivalTimeActualPackage]');
                                            break;
                                        case 10:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodScheduledTimePackage]');
                                            break;
                                        case 11:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodActualTimePackage]');
                                            break;
                                        case 12:
                                            checkboxes = document.querySelectorAll('input[name=tripPeriodDifferenceTimePackage]');
                                            break;
                                    }

                                    for (x = 0; x < checkboxes.length; x++) {
                                        checkboxes[x].checked = event.target.checked;
                                    }
                                }




//this is for filtering--------------------
                                var rows = document.getElementById("mainTableBody").rows;
                                var cloneRows = new Array();
                                for (y = 0; y < rows.length; y++) {
                                    var cloneRow = rows[y];
                                    cloneRows.push(cloneRow);
                                }

                                function filter() {

                                    var routeNumberCheckboxes = document.querySelectorAll('input[name=routeNumberPackage]:checked');
                                    var dateStampCheckboxes = document.querySelectorAll('input[name=dateStampPackage]:checked');
                                    var busNumberCheckboxes = document.querySelectorAll('input[name=busNumberPackage]:checked');
                                    var exodusNumberCheckboxes = document.querySelectorAll('input[name=exodusNumberPackage]:checked');
                                    var driverNameCheckboxes = document.querySelectorAll('input[name=driverNamePackage]:checked');
                                    var tripPeriodTypeCheckboxes = document.querySelectorAll('input[name=tripPeriodTypePackage]:checked');
                                    var startTimeScheduledCheckboxes = document.querySelectorAll('input[name=startTimeScheduledPackage]:checked');
                                    var startTimeActualCheckboxes = document.querySelectorAll('input[name=startTimeActualPackage]:checked');
                                    var arrivalTimeScheduledCheckboxes = document.querySelectorAll('input[name=arrivalTimeScheduledPackage]:checked');
                                    var arrivalTimeActualCheckboxes = document.querySelectorAll('input[name=arrivalTimeActualPackage]:checked');
                                    var tripPeriodScheduledTimeCheckboxes = document.querySelectorAll('input[name=tripPeriodScheduledTimePackage]:checked');
                                    var tripPeriodActualTimeCheckboxes = document.querySelectorAll('input[name=tripPeriodActualTimePackage]:checked');
                                    var tripPeriodDifferenceTimeCheckboxes = document.querySelectorAll('input[name=tripPeriodDifferenceTimePackage]:checked');

                                    var routeNumberArray = new Array();
                                    var dateStampArray = new Array();
                                    var busNumberArray = new Array();
                                    var exodusNumberArray = new Array();
                                    var driverNameArray = new Array();
                                    var tripPeriodTypeArray = new Array();
                                    var startTimeScheduledArray = new Array();
                                    var startTimeActualArray = new Array();


                                    var arrivalTimeScheduledArray = new Array();
                                    var arrivalTimeActualArray = new Array();

                                    var tripPeriodScheduledTimeArray = new Array();
                                    var tripPeriodActualTimeArray = new Array();
                                    var tripPeriodDifferenceTimeArray = new Array();


                                    for (x = 0; x < routeNumberCheckboxes.length; x++) {
                                        routeNumberArray.push(routeNumberCheckboxes[x].value)
                                    }
                                    for (x = 0; x < dateStampCheckboxes.length; x++) {
                                        dateStampArray.push(dateStampCheckboxes[x].value)
                                    }
                                    for (x = 0; x < busNumberCheckboxes.length; x++) {
                                        busNumberArray.push(busNumberCheckboxes[x].value)
                                    }
                                    for (x = 0; x < exodusNumberCheckboxes.length; x++) {
                                        exodusNumberArray.push(exodusNumberCheckboxes[x].value)
                                    }
                                    for (x = 0; x < driverNameCheckboxes.length; x++) {
                                        driverNameArray.push(driverNameCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodTypeCheckboxes.length; x++) {
                                        tripPeriodTypeArray.push(tripPeriodTypeCheckboxes[x].value)
                                    }
                                    for (x = 0; x < startTimeScheduledCheckboxes.length; x++) {
                                        startTimeScheduledArray.push(startTimeScheduledCheckboxes[x].value)
                                    }
                                    for (x = 0; x < startTimeActualCheckboxes.length; x++) {
                                        startTimeActualArray.push(startTimeActualCheckboxes[x].value)
                                    }

                                    for (x = 0; x < arrivalTimeScheduledCheckboxes.length; x++) {
                                        arrivalTimeScheduledArray.push(arrivalTimeScheduledCheckboxes[x].value)
                                    }
                                    for (x = 0; x < arrivalTimeActualCheckboxes.length; x++) {
                                        arrivalTimeActualArray.push(arrivalTimeActualCheckboxes[x].value)
                                    }

                                    for (x = 0; x < tripPeriodScheduledTimeCheckboxes.length; x++) {
                                        tripPeriodScheduledTimeArray.push(tripPeriodScheduledTimeCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodActualTimeCheckboxes.length; x++) {
                                        tripPeriodActualTimeArray.push(tripPeriodActualTimeCheckboxes[x].value)
                                    }
                                    for (x = 0; x < tripPeriodDifferenceTimeCheckboxes.length; x++) {
                                        tripPeriodDifferenceTimeArray.push(tripPeriodDifferenceTimeCheckboxes[x].value)
                                    }



                                    mainTableBody.innerHTML = "";

                                    for (x = 0; x < cloneRows.length; x++) {
                                        var cells = cloneRows[x].getElementsByTagName("td");
                                        if (cells.length <= 1) {//captures
                                            if (captures.checked) {
                                                mainTableBody.appendChild(cloneRows[x]);
                                            }
                                        } else {

                                            if (routeNumberArray.includes(cells[0].innerHTML)
                                                    && dateStampArray.includes(cells[1].innerHTML)
                                                    && busNumberArray.includes(cells[2].innerHTML)
                                                    && exodusNumberArray.includes(cells[3].innerHTML)
                                                    && driverNameArray.includes(cells[4].innerHTML)
                                                    && tripPeriodTypeArray.includes(cells[5].innerHTML)
                                                    && startTimeScheduledArray.includes(cells[6].innerHTML)
                                                    && startTimeActualArray.includes(cells[7].innerHTML)
                                                    && arrivalTimeScheduledArray.includes(cells[8].innerHTML)
                                                    && arrivalTimeActualArray.includes(cells[9].innerHTML)
                                                    && tripPeriodScheduledTimeArray.includes(cells[10].innerHTML)
                                                    && tripPeriodActualTimeArray.includes(cells[11].innerHTML)
                                                    && tripPeriodDifferenceTimeArray.includes(cells[12].innerHTML)) {

                                                mainTableBody.appendChild(cloneRows[x]);

                                            }
                                        }
                                    }
                                    calculateAndDisplayAverage();
                                }

                                //here calculations of averages

                                calculateAndDisplayAverage();

                                function calculateAndDisplayAverage() {
                                    var calculationRows = document.getElementById("mainTableBody").rows;
                                    calculationsTableBody.innerHTML = "";
                                    let routes = new Map();
                                    let cells;
                                    let routeNumber;
                                    let tripPeriodType;
                                    let tripPeriodTimeScheduled;
                                    let tripPeriodTimeActual;
                                    let tripPeriodTimeScheduledInSeconds;
                                    let tripPeriodTimeActualInSeconds;
                                    for (x = 0; x < calculationRows.length; x++) {
                                        cells = calculationRows[x].getElementsByTagName("td");
                                        routeNumber = cells[0].innerHTML;
                                        tripPeriodType = cells[5].innerHTML;
                                        if (tripPeriodType == "A_B" || tripPeriodType == "B_A") {
                                            tripPeriodTimeScheduled = cells[10].innerHTML;
                                            tripPeriodTimeActual = cells[11].innerHTML;
                                            if (tripPeriodTimeActual != "" && percentageChecks(tripPeriodTimeScheduled, tripPeriodTimeActual)) {
                                                if (routes.has(routeNumber)) {
                                                    if (tripPeriodType == "A_B") {
                                                        tripPeriodTimeActualInSeconds = convertTimeStampIntoSeconds(tripPeriodTimeActual);
                                                        let route = routes.get(routeNumber);
                                                        route.ab_count = route.ab_count + 1;
                                                        route.ab_tripPeriodTimeTotal += tripPeriodTimeActualInSeconds;
                                                        if (route.ab_tripPeriodTimeStandart == "") {
                                                            route.ab_tripPeriodTimeStandart = tripPeriodTimeScheduled;
                                                        } else {
                                                            if (route.ab_tripPeriodTimeStandart != tripPeriodTimeScheduled) {
                                                                route.ab_tripPeriodTimeStandart = "multiStandart";
                                                            }
                                                        }

                                                    } else {
                                                        tripPeriodTimeActualInSeconds = convertTimeStampIntoSeconds(tripPeriodTimeActual);
                                                        let route = routes.get(routeNumber);
                                                        route.ba_count = route.ba_count + 1;
                                                        route.ba_tripPeriodTimeTotal += tripPeriodTimeActualInSeconds;
                                                        if (route.ba_tripPeriodTimeStandart == "") {
                                                            route.ba_tripPeriodTimeStandart = tripPeriodTimeScheduled;
                                                        } else {
                                                            if (route.ba_tripPeriodTimeStandart != tripPeriodTimeScheduled) {
                                                                route.ba_tripPeriodTimeStandart = "multiStandart";
                                                            }
                                                        }
                                                    }

                                                } else {
                                                    if (tripPeriodType == "A_B") {
                                                        tripPeriodTimeActualInSeconds = convertTimeStampIntoSeconds(tripPeriodTimeActual);
                                                        let route = {routeNumber: routeNumber, ab_count: 1, ab_tripPeriodTimeStandart: tripPeriodTimeScheduled, ab_tripPeriodTimeTotal: tripPeriodTimeActualInSeconds, ba_count: 0, ba_tripPeriodTimeStandart: "", ba_tripPeriodTimeTotal: 0};
                                                        routes.set(routeNumber, route);
                                                    } else {
                                                        tripPeriodTimeActualInSeconds = convertTimeStampIntoSeconds(tripPeriodTimeActual);
                                                        let route = {routeNumber: routeNumber, ab_count: 0, ab_tripPeriodTimeStandart: "", ab_tripPeriodTimeTotal: 0, ba_count: 1, ba_tripPeriodTimeStandart: tripPeriodTimeScheduled, ba_tripPeriodTimeTotal: tripPeriodTimeActualInSeconds};
                                                        routes.set(routeNumber, route);
                                                    }
                                                }

                                            }
                                        }
                                    }





                                    let trs = "";
                                    let ab_count;
                                    let ab_standartTime;
                                    let ab_totalTotalTime;
                                    let ab_averageTime;

                                    let ba_count;
                                    let ba_standartTime;
                                    let ba_totalTotalTime;
                                    let ba_averageTime;

                                    let both_count;
                                    let both_averageTime;
                                    let both_averageTimeInSeconds;
                                    for (let [routeNumber, route] of routes) {
                                        ab_count = route.ab_count;
                                        ab_standartTime = route.ab_tripPeriodTimeStandart;
                                        ab_totalTotalTime = route.ab_tripPeriodTimeTotal;
                                        ab_averageTime = calculateAverage(ab_totalTotalTime, ab_count);
                                        ba_count = route.ba_count;
                                        ba_standartTime = route.ba_tripPeriodTimeStandart;
                                        ba_totalTotalTime = route.ba_tripPeriodTimeTotal;
                                        ba_averageTime = calculateAverage(ba_totalTotalTime, ba_count);
                                        both_count = ab_count + ba_count;
                                        if (ab_averageTime != "" && ba_averageTime != "") {
                                            both_averageTimeInSeconds = convertTimeStampIntoSeconds(ab_averageTime) + convertTimeStampIntoSeconds(ba_averageTime)
                                        } else {
                                            both_averageTimeInSeconds = "";
                                        }
                                        both_averageTime = calculateAverage(both_averageTimeInSeconds, 1);
                                        trs += "<tr><td rowspan='2'>" + routeNumber + "</td><td>A_B</td><td>-</td><td>-</td><td>-</td><td>" + ab_count + "</td><td>" + ab_standartTime + "</td><td>" + ab_averageTime + "</td><td rowspan='2'>" + both_count + "</td><td rowspan='2'>" + both_averageTime + "</td></tr>"
                                                + "<tr><td>B_A</td><td>-</td><td>-</td><td>-</td><td>" + ba_count + "</td><td>" + ba_standartTime + "</td><td>" + ba_averageTime + "</td></tr>";


                                    }


                                    calculationsTableBody.innerHTML = trs;
                                    let perc = percentage.value;
                                    percentDisplay.innerHTML = "გამოთვლისთვის არის გამოყენებული მხოლოდ A-B და B-A წირები, რომლების გეგმიური და ფაქტიური დროს შორის აცდენა უდრის ან ნაკლებია( =< ) " + perc + "%-ს";

                                }




                                function convertTimeStampIntoSeconds(timeStamp) {
                                    var timeArray = timeStamp.split(":");
                                    var seconds = (timeArray[0] * 60 * 60) + (timeArray[1] * 60) + timeArray[2] * 1;
                                    return seconds;

                                }

                                function calculateAverage(totalSeconds, count) {
                                    if (totalSeconds == "") {
                                        return "";
                                    }

                                    var averageSeconds = Math.round(totalSeconds / count);
                                    var hours = Math.trunc(averageSeconds / 3600);
                                    var remainder = averageSeconds - hours * 3600;
                                    var minutes = Math.trunc(remainder / 60);
                                    remainder = remainder - minutes * 60;
                                    var seconds = remainder;
                                    if (hours < 10) {
                                        hours = "0" + hours;
                                    }
                                    if (minutes < 10) {
                                        minutes = "0" + minutes;
                                    }
                                    if (seconds < 10) {
                                        seconds = "0" + seconds;
                                    }
                                    return hours + ":" + minutes + ":" + seconds;

                                }

                                function percentageChecks(tripPeriodTimeScheduled, tripPeriodTimeActual) {
                                    let percentage = document.getElementById("percentage").value;

                                    let  tripPeriodTimeScheduledInSeconds = convertTimeStampIntoSeconds(tripPeriodTimeScheduled);
                                    let  tripPeriodTimeActualInSeconds = convertTimeStampIntoSeconds(tripPeriodTimeActual);
                                    let difference = Math.abs(tripPeriodTimeScheduledInSeconds - tripPeriodTimeActualInSeconds);
                                    if (difference <= (tripPeriodTimeScheduledInSeconds / 100) * percentage) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }


                                //submit for excelExprot

                                function collectAndSubmit() {

                                    let rn = collectSellectedCheckBoxes(".routeNumberPackage");
                                    let ds = collectSellectedCheckBoxes(".dateStampPackage");


                                    let bn = collectSellectedCheckBoxes(".busNumberPackage");
                                    let en = collectSellectedCheckBoxes(".exodusNumberPackage");

                                    let dn = collectSellectedCheckBoxes(".driverNamePackage");
                                    let tpt = collectSellectedCheckBoxes(".tripPeriodTypePackage");

                                    let sts = collectSellectedCheckBoxes(".startTimeScheduledPackage");
                                    let sta = collectSellectedCheckBoxes(".startTimeActualPackage");

                                    let ats = collectSellectedCheckBoxes(".arrivalTimeScheduledPackage");
                                    let ata = collectSellectedCheckBoxes(".arrivalTimeActualPackage");

                                    let tps = collectSellectedCheckBoxes(".tripPeriodScheduledTimePackage");
                                    let tpa = collectSellectedCheckBoxes(".tripPeriodActualTimePackage");
                                    let tpd = collectSellectedCheckBoxes(".tripPeriodDifferenceTimePackage");


                                    routeNumber.value = rn;
                                    dateStamp.value = ds;
                                    busNumber.value = bn;
                                    exodusNumber.value = en;
                                    driverName.value = dn;
                                    tripPeriodType.value = tpt;
                                    startTimeScheduled.value = sts;
                                    startTimeActual.value = sta;
                                    arrivalTimeScheduled.value = ats;
                                    arrivalTimeActual.value = ata;
                                    tripPeriodScheduled.value = tps;
                                    tripPeriodActual.value = tpa;
                                    tripPeriodDifference.value = tpd;
                                    percents.value = percentage.value;

                                    excelExportForm.submit();


                                }

                                function collectSellectedCheckBoxes(className) {
                                    var returnValue = "";
                                    var targetCheckBoxes = document.querySelectorAll(className);
                                    for (x = 0; x < targetCheckBoxes.length; x++) {
                                        if (targetCheckBoxes[x].checked)
                                            returnValue += targetCheckBoxes[x].value + ",";
                                    }
                                    return returnValue;
                                }

        </script>
    </body>
</html>
