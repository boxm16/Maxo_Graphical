<?php
require_once 'Controller/RouteDBController.php';
if (isset($_GET["routeNumber"]) && isset($_GET["dateStamps"]) && isset($_GET["type"]) && isset($_GET["percents"]) && isset($_GET["height"])) {
    $routeNumber = $_GET["routeNumber"];
    $dateStamps = $_GET["dateStamps"];
    $tripPeriodType = $_GET["type"];
    $percents = $_GET["percents"];
    $height = $_GET["height"];
    if ($height == "low") {
        $percentSign = "+";
    }
    if ($height == "high") {
        $percentSign = "-";
    }
    if ($height == "both") {
        $percentSign = "+/-";
    }
    $routeController = new RouteDBController();
    $tripPeriods = $routeController->getRequestedTripPeriods($routeNumber, $dateStamps, $tripPeriodType, $percents, $height);
    $countedPeriodsCount = count($tripPeriods);
    $countedTripPeriodsDetails = " ჩათვლილი ბრუნების რაოდენობა:$countedPeriodsCount, პროცენტული ზღვარი:$percentSign$percents%";
} else {
    $exodusDetails = "რაღაც შეცდომა მოხდა, სცადე თავიდან";
}
?>



<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
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

            th {
                vertical-align: bottom;
                background-color: #D170F7;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div style="background-color:green;color:white"> <center><h1> <?php echo $countedTripPeriodsDetails; ?> </h1></center></div>


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
?>





            </tbody>
        </table>

    </body>
</html>
