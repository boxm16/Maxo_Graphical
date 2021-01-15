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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>

            table, th, td {
                border: 1px solid black;
                border-collapse: collapse
            }

            .navbar {
                overflow: hidden;
                background-color: lightgreen;
                position: fixed;
                top: 0;
                width: 100%;
                height: 50px;
            }
            form{
                padding: 8px 15px;
            }


            .navbar a {
                float: left;
                display: block;
                color: #f2f2f2;
                text-align: center ;
                color: black;
                padding: 6px 15px;
                text-decoration: none;
                font-size: 17px;
            }

            .navbar a:hover {
                background: #ddd;
                color: black;
            }

        </style>
    </head>
    <body>


        <div class="navbar">
            <a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
            <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a>
            <a href="intervals.php">ინტერვალები</a>
            <a href="concurrentlyHaltedBuses.php">ერთდროულად მდგომი ავტობუსები</a>


            <form action='excelExport.php' method='POST'><input type='hidden' name='export' value='export'><input type='submit' value='ექსელში ექსპორტი' ></form>

        </div>
        <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
        <div style="padding-left: 10px">
            <?php
            foreach ($routes as $route) {
                echo "<hr>მარშრუტი N: " . $route->getNumber() . "<br>";
                $days = $route->getDays();

                foreach ($days as $day) {

                    echo $day->getDateStamp() . "<hr>";

                    $exoduses = $day->getExoduses();

                    foreach ($exoduses as $exodus) {
                        $tableConstructor = "<table style='width:400px'><tbody>";

                        $tableConstructor .= "<tr>"
                                . "<td colspan='14' style=' text-align: center; '><label><b>გასვლა N:" . $exodus->getNumber() . "</b></label></td>"
                                . "</tr>";
                        $tripVouchers = $exodus->getTripVouchers();
                        foreach ($tripVouchers as $tripVoucher) {



                            $tableConstructor .= "<tr>"
                                    . "<th colspan='3'>გასვლის დრო</th>"
                                    . "<th></th>"
                                    . "<th colspan='3'>მისვლის დრო</th>"
                                    . "<th style='background-color:black'></th>"
                                    . "<th colspan='5'  style=' text-align: center; '>გამოთვლები</th>"
                                    . "<th rowspan='2'>რიგის ცალკე ნახვისთვის</th>"
                                    . "</tr>"
                                    . "<tr>"
                                    . "<th>გეგმიუირი</th>"
                                    . "<th>ფაქტიური</th>"
                                    . "<th>სხვაობა</th>"
                                    . "<th>------</th>"
                                    . "<th>გეგმიუირი</th>"
                                    . "<th>ფაქტიური</th>"
                                    . "<th>სხვაობა</th>"
                                    . "<th style='background-color:black'>-</th>"
                                    . "<th>ბრუნის(წირის) გეგმიური დრო</th>"
                                    . "<th>ბრუნის(წირის) ფაქტიური დრო</th>"
                                    . "<th>დგომის გეგმიური დრო</th>"
                                    . "<th>დგომის ფაქტიური დრო</th>"
                                    . "<th>'დაკარგული დრო'</th>"
                                    . "</tr>";
                            $tripPeriods = $tripVoucher->getTripPeriods();
                            foreach ($tripPeriods as $tripPeriod) {
                                $timeStamp = $tripPeriod->getLostTime();
                                $lightsForLostTime = $tripPeriod->getLightsForTimeStamp($timeStamp);
                                $tripPeriodType = $tripPeriod->getType();
                                $rowColor = "white";
                                if ($tripPeriodType == "break") {
                                    $rowColor = "LightGray";
                                }
                                $tableConstructor .= "<tr style='background-color:$rowColor'>"
                                        . "<td>" . $tripPeriod->getStartTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getStartTimeActual() . "</td>"
                                        . "<td>" . $tripPeriod->getStartTimeDifference() . "</td>"
                                        . "<td>" . $tripPeriod->getTypeGe() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeScheduled() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeActual() . "</td>"
                                        . "<td>" . $tripPeriod->getArrivalTimeDifference() . "</td>"
                                        . "<td style='background-color:black'></td>"
                                        . "<td>" . $tripPeriod->getScheduledTripPeriodTime() . "</td>"
                                        . "<td>" . $tripPeriod->getActualTripPeriodTime() . "</td>"
                                        . "<td>" . $tripPeriod->getScheduledHaltTime() . "</td>"
                                        . "<td>" . $tripPeriod->getActualHaltTime() . "</td>"
                                        . "<td style='background-color:$lightsForLostTime'>" . $tripPeriod->getLostTime() . "</td>"
                                        . "<td><button type='button' style='width:100%; background-color:lightgreen' data-toggle='modal' data-target='#exampleModalCenter' onclick='copyData(event)'>დაკლიკე</button></td>"
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


            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered " style="max-width: 95%;" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table style='width:400px'>

                                <thead>
                                    <tr>
                                        <th colspan='3'>გასვლის დრო</th>
                                        <th></th>
                                        <th colspan='3'>მისვლის დრო</th>
                                        <th style='background-color:black'></th>
                                        <th colspan='5'  style=' text-align: center; '>გამოთვლები</th>

                                    </tr>
                                    <tr>
                                        <th>გეგმიუირი</th>
                                        <th>ფაქტიური</th>
                                        <th>სხვაობა</th>
                                        <th>------</th>
                                        <th>გეგმიუირი</th>
                                        <th>ფაქტიური</th>
                                        <th>სხვაობა</th>
                                        <th style='background-color:black'>-</th>
                                        <th>ბრუნის(წირის) გეგმიური დრო</th>
                                        <th>ბრუნის(წირის) ფაქტიური დრო</th>
                                        <th>დგომის გეგმიური დრო</th>
                                        <th>დგომის ფაქტიური დრო</th>
                                        <th>'დაკარგული დრო'</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">

                                </tbody>

                            </table>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">ფანჯრის დახურვა</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            function copyData(event) {
                tableBody.innerHTML = "";
                var trElements = event.target.parentElement.parentElement;
                var clone = trElements.cloneNode(true); // copy children too
                clone.removeChild(clone.childNodes[13]);
                tableBody.appendChild(clone); // add new row to end of table

            }
        </script>
    </body>
</html>
