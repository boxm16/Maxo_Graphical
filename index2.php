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

            tr  {
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


                    <form id="form" action="deletion.php" method="POST">
                        <button type="submit" class="btn btn-success" style="font-size: 20px" onclick="requestRouter('routeDetails.php')">ბრუნების ნახვა</button>
                        <button type="submit" class="btn btn-warning" style="font-size: 20px" onclick="requestRouter('intervals.php')">ინტერვალების ნახვა</button>
                        <button type="submit" class="btn btn-secondery" style="font-size: 20px" onclick="requestRouter('deletion.php')">ექსელის ფორმა</button>

                        <input type="text" id="routes_dates" name="routes:dates">

                    </form>
                    <hr>


                    <?php
                    if (count($routes) > 1) {
                        echo "<center><h2>აირჩიე მარშრუტი</h2></center>";
                    }
                    $bigTableRowBuilder = "";
                    foreach ($routes as $route) {
                        $routeNumber = $route->getNumber();
                        ?><table style="text-align:center; font-size:25px">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" style="width:28px;height:28px" onclick="selectRouteAllDates(event, '<?php echo $routeNumber; ?>')" checked="true">
                                    </th>
                                    <th>
                                        &nbsp&nbsp   მარშრუტი # <?php echo $routeNumber; ?>   &nbsp&nbsp   &nbsp&nbsp 
                                    </th>
                                    <th>
                                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#<?php echo $routeNumber; ?>" aria-expanded="false" aria-controls="<?php echo $routeNumber; ?>">
                                            +
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                        </table>


                        <div class="collapse" id="<?php echo $routeNumber; ?>">

                            <table style="text-align:center; font-size:20px" id="daysOfRoute<?php echo $routeNumber; ?>">
                                <tbody>
                                    <?php
                                    $days = $route->getDays();
                                    foreach ($days as $day) {
                                        $dateStamp = $day->getDateStamp();
                                        echo "<tr>"
                                        . "<td>"
                                        . "&nbsp&nbsp&nbsp"
                                        . "</td>"
                                        . "<td>"
                                        . "<input type=\"checkbox\" class=\"dates\" checked=\"true\" value=\"$routeNumber:$dateStamp\"> "
                                        . "</td>"
                                        . "<td colspan=\"2\">"
                                        . "&nbsp&nbsp$dateStamp"
                                        . "</td>"
                                        . "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>


                        </div> 


                        </table><?php
                    }
                    ?>


                    <hr><hr>

                </div>

            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
                                        function requestRouter(requestTarget) {
                                            form.action = requestTarget;
                                            routes_dates.value = collectSellectedCheckBoxes();
                                            console.log(form.action);
                                        }

                                        function selectRouteAllDates(event, routeNumber) {
                                            console.log(routeNumber);
                                            var checkbox = event.target;
                                            var table = document.getElementById("daysOfRoute" + routeNumber);
                                            var targetCheckBoxes = table.querySelectorAll("input[type=\"checkbox\"]");

                                            for (x = 0; x < targetCheckBoxes.length; x++) {
                                                targetCheckBoxes[x].checked = checkbox.checked;

                                            }

                                        }
//this function collects all checked checkbox values, concatinates them in one string and returns that string to send it after by POST method to server
                                        function collectSellectedCheckBoxes() {
                                            var returnValue = "";
                                            var targetCheckBoxes = document.querySelectorAll(".dates");
                                            for (x = 0; x < targetCheckBoxes.length; x++) {
                                                if (targetCheckBoxes[x].checked)
                                                    returnValue += targetCheckBoxes[x].value + ",";
                                            }
                                            return returnValue;
                                        }
        </script>
    </body>
</html>
