<?php
require_once 'Controller/RouteXLController.php';
$routesController = new RouteXLController();
$routes = $routesController->getRoutes();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>მახო</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <style>
            th {
                text-align: center;
            }


        </style>
    </head>
    <body>

        <div class="container">
            <div class="row">

                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <a class="btn btn-primary" href="uploadForm.php" style="font-size: 20px">ახალი ფაილის ატვირთვა</a>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <form action="deletion.php" method="POST">
                                    <button type="submit" class="btn btn-success" style="font-size: 20px" onclick="collectInputs('routeDetailsInput')">ბრუნების ნახვა</button>
                                    <input type="hidden" name="routeNumber" id="routeDetailsInput_RouteNumber">
                                    <input type="hidden" name="days" id="routeDetailsInput_Days">
                                </form>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <form action="deletion.php" method="POST">
                                    <button type="submit" class="btn btn-success" style="font-size: 20px" onclick="collectInputs('intervalsInput')">ინტერვალების ნახვა</button>
                                    <input type="hidden" name="routeNumber" id="intervalsInput_RouteNumber" value=""> 
                                    <input type="hidden"  name="days" id="intervalsInput_Days" value=""> 
                                </form>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <hr>
                <table class="table">

                    <?php
                    $tableBuilder = "";
                    $headBuilder = "";
                    $bodyBuilder = "";
                    $thColspan = count($routes);
                    foreach ($routes as $route) {
                        $routeNumber = $route->getNumber();
                        if (count($routes) == 1) {
                            $headBuilder .= "<th><input type=\"radio\" name=\"routeNumber\" value=\"$routeNumber\" checked > # $routeNumber</th>";
                            $disabled = "";
                        } else {
                            $headBuilder .= "<th><input type=\"radio\" name=\"routeNumber\" value=\"$routeNumber\" onclick=\"go($routeNumber)\"> $routeNumber</th>";
                            $disabled = "disabled";
                        }
                        $days = $route->getDays();
                        $insideBodyBuilder = "";
                        foreach ($days as $day) {
                            $dateStamp = $day->getDateStamp();
                            $insideBodyBuilder .= "<tr><td><input  type=\"checkbox\" name=\"$routeNumber\" checked $disabled value=\"$dateStamp\"> $dateStamp </td></tr>";
                        }
                        $bodyBuilder .= "<td><table  class=\"table\"><thead></thead><tbody>$insideBodyBuilder</tbody></table></td>";
                    }
                    $headBuilder = "<thead><tr><th colspan=\"$thColspan\"><h3>აირჩიე მარშრუტი</h3></th></tr><tr>$headBuilder</tr></thead>";
                    $tableBuilder .= $headBuilder . "<tbody><tr>$bodyBuilder</tr></tbody>";
                    echo $tableBuilder;
                    ?>
                </table>


            </div>
        </div>
        <script>
            function go(routeNumber) {
                checkboxes = document.querySelectorAll("input[name='" + routeNumber + "']");
                allCheckBoxes = document.querySelectorAll("input[type=checkbox]");
                for (x = 0; x < allCheckBoxes.length; x++) {
                    allCheckBoxes[x].disabled = true;
                }
                for (x = 0; x < checkboxes.length; x++) {
                    checkboxes[x].disabled = false;
                }
            }

            function collectInputs(inputs) {
                var selectedDays = document.querySelectorAll("input[type=checkbox]:checked:not(:disabled)");
                var daysInputBuilder = "";
                for (x = 0; x < selectedDays.length; x++) {
                    daysInputBuilder += selectedDays[x].value;
                    if (x < selectedDays.length - 1) {
                        daysInputBuilder += ",";
                    }
                }
                var selectedRouteNumber = document.querySelector("input[type=radio]:checked");

                var selectedRouteNumber = selectedRouteNumber.value;


                if (inputs == "routeDetailsInput") {
                    routeDetailsInput_RouteNumber.value = selectedRouteNumber;
                    routeDetailsInput_Days.value = daysInputBuilder;

                }
                if (inputs == "intervalsInput") {
                    intervalsInput_RouteNumber.value = selectedRouteNumber;
                    intervalsInput_Days.value = daysInputBuilder;
                }

            }

        </script>
    </body>
</html>
