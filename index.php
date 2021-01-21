<?php
require_once 'Controller/RouteXLController.php';
$routesController = new RouteXLController();
$routes = $routesController->getFullRoutes();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>

            input[type="checkbox"]{
                width: 20px; /*Desired width*/
                height: 20px; /*Desired height*/
            }

            tr {
                border:solid black 1px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class=""row>
                <div class="col">
                    <hr>
                    <a class="btn btn-primary" href="uploadForm.php" style="font-size: 20px">ახალი ფაილის ატვირთვა</a>

                    <hr>
                    <?php
                    if (count($routes) > 1) {
                        echo "<center><h2>აირჩიე მარშრუტი</h2></center>";
                    }
                    $bigTableRowBuilder = "";
                    foreach ($routes as $route) {
                        $days = $route->getDays();
                        $routeNumber = $route->getNumber();
                        $bodyBuilder = "";
                        foreach ($days as $day) {
                            $dateStamp = $day->getDateStamp();
                            $checkboxInput = "<input name=\"dates[]\" type=\"checkbox\" value=\"$dateStamp\" checked>";
                            $bodyBuilder .= "<tr><td>$checkboxInput</td><td style=\"font-size:20px\">$dateStamp</td></tr>";
                        }

                        $routeDetailsButton = " <button type=\"submit\" class=\"btn btn-success\" style=\"font-size: 20px\" onclick=\"requestRouter(event, 'routeDetails.php')\">ბრუნების ნახვა</button>";


                        $intervalsButton = " <button type=\"submit\" class=\"btn btn-warning\" style=\"font-size: 20px\" onclick=\"requestRouter(event, 'intervals.php')\">ინტერვალების ნახვა</button>";


                        $hiddenInputRouteNumber = "<input name=\"routeNumber\" type=\"hidden\" value=\"$routeNumber\">";
                        $routeTable = "<table><thead><tr><th colspan = \"2\"  style=\"text-align:center; font-size:25px\">$hiddenInputRouteNumber მარშრუტი #$routeNumber</th></tr><tr><th>$routeDetailsButton</th><th>$intervalsButton</th></tr><tr><th><input type=\"checkbox\" checked onclick=\"selectAllDates(event)\"></th><th>ყველა დღე</th></tr></thead><tbody>$bodyBuilder</tbody></table>";

                        $form = "<form action=\"\" method=\"POST\">$routeTable</form>";

                        $bigTableRowBuilder .= "<td style=\"  vertical-align: top;\">$form</td>";
                    }
                    $bigTable = "<table id=\"bigTable\"><tr>$bigTableRowBuilder</tr></table>";
                    echo $bigTable;
                    ?>

                </div>

            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            function requestRouter(event, requestTarget) {
                var form = event.target.parentNode.parentNode.parentNode.parentNode.parentNode;
                form.action = requestTarget;
                console.log(form.action);
            }

            function selectAllDates(event) {
                var checkbox = event.target;
                var table = event.target.parentNode.parentNode.parentNode.parentNode;
                var targetCheckBoxes = table.querySelectorAll("input[type=\"checkbox\"]");
                console.log(targetCheckBoxes.length);
                for (x = 0; x < targetCheckBoxes.length; x++) {
                    targetCheckBoxes[x].checked = checkbox.checked;
                    console.log(targetCheckBoxes[x].checked);
                }

            }
        </script>
    </body>
</html>
