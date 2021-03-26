<?php
require_once 'Controller/RouteDBController.php';
require_once 'Controller/RouteXLController.php'; //one of this imports needed
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
$s = microtime(true);

if (isset($_POST["filter"])) {
   echo "koko";
    exit;
} else {

    $routeController = new RouteDBController();
    $excelFormPackage = $routeController->getExcelFormPackage($requestedRoutesAndDates);
}
/*
  $routeController = new RouteXLController();
  $excelFormPackage = $routeController->getExcelFormPackage($clientId, $requestedRoutesAndDates);
 */

$e = microtime(true);
echo "Time required=" . ($e - $s);
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
        <title></title>
        <style>
            table {

                border-collapse: collapse;
                border: 0.1em solid #d6d6d6;
            }

            th {
                vertical-align: bottom;
                background-color: green;
                color: #fff;
            }
            table, thead, tr, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }

        </style>
    </head>
    <body>
        <?php
        $button = "<button type=\"button\" style=\"background-color:lightblue;\" onclick=\"collectAndSubmit()\">გაფილტრვა</button>";
        ?>
        <div>
            <form id="averagesForm" action="averages.php" method="POST">
                <input name="filter" hidden>
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


            <h5 >ფილტრები</h5>

        </div>


        <div>
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
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
                                    echo "<tr>$button</tr>";
                                    ?> 
                                </tbody>
                            </table>
                        </td>


                    </tr>
                </tbody>
            </table>
        </div>
        <script>

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


                averagesForm.submit();


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
