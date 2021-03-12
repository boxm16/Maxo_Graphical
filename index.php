<?php
require_once 'Controller/RouteXLController.php';
require_once 'clientId.php'; //here i take clientId from cookie, or set new

$routesController = new RouteXLController();
$routes = $routesController->getFullRoutes($clientId);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>საწყისი გვერდი</title>
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



            .sidebar-container {
                position: fixed;
                width: 220px;
                height: 100%;
                left: 0;
                overflow-x: hidden;
                overflow-y: auto;
                background: #1a1a1a;
                color: #fff;
            }

            .content-container {
                padding-top: 20px;
            }

            .sidebar-logo {
                padding: 10px 15px 10px 30px;
                font-size: 20px;
                background-color: #2574A9;
            }

            .sidebar-navigation {
                padding: 0;
                margin: 0;
                list-style-type: none;
                position: relative;
            }

            .sidebar-navigation li {
                background-color: transparent;
                position: relative;
                display: inline-block;
                width: 100%;
                line-height: 20px;
            }

            .sidebar-navigation li a {
                padding: 10px 15px 10px 30px;
                display: block;
                color: #fff;
            }

            .sidebar-navigation li .fa {
                margin-right: 10px;
            }

            .sidebar-navigation li a:active,
            .sidebar-navigation li a:hover,
            .sidebar-navigation li a:focus {
                text-decoration: none;
                outline: none;
            }

            .sidebar-navigation li::before {
                background-color: #2574A9;
                position: absolute;
                content: '';
                height: 100%;
                left: 0;
                top: 0;
                -webkit-transition: width 0.2s ease-in;
                transition: width 0.2s ease-in;
                width: 3px;
                z-index: -1;
            }

            .sidebar-navigation li:hover::before {
                width: 100%;
            }

            .sidebar-navigation .header {
                font-size: 12px;
                text-transform: uppercase;
                background-color: #151515;
                padding: 10px 15px 10px 30px;
            }

            .sidebar-navigation .header::before {
                background-color: transparent;
            }

            .content-container {
                padding-left: 220px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class=""row>
                <div class="col">
                    <nav class="navbar fixed-top navbar-light bg-light">
                        <a class="btn btn-primary" href="uploadForm.php" style="font-size: 20px">ახალი ფაილის ატვირთვა</a>
                        <table>
                            <thead>
                            <th>
                                <input type="checkbox" id="mainCheckBox" style="width:28px;height:28px"  checked="true" onclick="selectRouteAll(event)">
                            </th>
                            <th style="width:200px">
                                ყველა
                            </th>
                            <th>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#<?php echo $routeNumber; ?>" aria-expanded="false" aria-controls="<?php echo $routeNumber; ?>">
                                    +
                                </button>
                            </th>
                            </thead>
                        </table>
                        <form id="form" action="deletion.php" method="POST">
                            <button type="submit" class="btn btn-success" style="font-size: 20px" onclick="requestRouter('routeDetails.php')">ბრუნების ნახვა</button>
                            <button type="submit" class="btn btn-warning" style="font-size: 20px" onclick="requestRouter('intervals.php')">ინტერვალების ნახვა</button>
                            <button type="submit" class="btn btn-secondery" style="font-size: 20px" onclick="requestRouter('excelForm.php')">ექსელის ფორმა</button>

                            <input hidden type="text" id="routes_dates" name="routes:dates">

                        </form>
                    </nav>
                    <hr>
                    &nbsp<hr>

                    <div class="sidebar-container">
                        <div class="sidebar-logo">
                            Project Name
                        </div>
                        <ul class="sidebar-navigation">
                            <li class="header">Navigation</li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-home" aria-hidden="true"></i> Homepage
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
                                </a>
                            </li>
                            <li class="header">Another Menu</li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-users" aria-hidden="true"></i> Friends
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-cog" aria-hidden="true"></i> Settings
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> Information
                                </a>
                            </li>
                        </ul>
                    </div>




                    <div class="content-container">

                        <div class="container-fluid">

                            <!-- Main component for a primary marketing message or call to action -->
                            <div class="jumbotron">
                                <h1>Navbar example</h1>
                                <p>This example is a quick exercise to illustrate how the default, static and fixed to top navbar work. It includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>
                                <p>To see the difference between static and fixed top navbars, just scroll.</p>
                                <p>
                                    <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
                                </p>
                            </div>









                            <?php
                            if (count($routes) > 1) {
                                echo "<center><h2>აირჩიე მარშრუტი</h2></center>";
                            }
                            ?>

                            <hr>
                            <?php
                            $bigTableRowBuilder = "";
                            foreach ($routes as $route) {
                                $routeNumber = $route->getNumber();
                                ?><table style="text-align:center; font-size:25px">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="routes" id="routeCheckBox:<?php echo $routeNumber; ?>" style="width:28px;height:28px" onclick="selectRouteAllDates(event, '<?php echo $routeNumber; ?>')" checked="true">
                                            </th>
                                            <th style="width:500px">
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

                                    <table style="text-align:center; font-size:20px" id="daysOfRoute:<?php echo $routeNumber; ?>">
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
                                                . "<input type=\"checkbox\" class=\"dates\" checked=\"true\" value=\"$routeNumber:$dateStamp\" onclick=\"checkDayCheckBoxes(event)\"> "
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
                                                    var table = document.getElementById("daysOfRoute:" + routeNumber);
                                                    var targetCheckBoxes = table.querySelectorAll("input[type=\"checkbox\"]");

                                                    for (x = 0; x < targetCheckBoxes.length; x++) {
                                                        targetCheckBoxes[x].checked = checkbox.checked;
                                                    }
                                                    checkRouteCheckBoxes();

                                                }

                                                function selectRouteAll(event) {

                                                    var bigCheckBox = event.target;
                                                    var allCheckBoxes = document.querySelectorAll("input[type=\"checkbox\"]");
                                                    for (x = 0; x < allCheckBoxes.length; x++) {
                                                        allCheckBoxes[x].checked = bigCheckBox.checked;
                                                    }
                                                }

                                                function checkDayCheckBoxes(event) {
                                                    var targetTable = event.target.parentNode.parentNode.parentNode.parentNode;
                                                    var targetTableFullId = targetTable.id;
                                                    var targetTableIdArray = targetTableFullId.split(":");
                                                    var routeNumber = targetTableIdArray[1];
                                                    var routeCheckBox = document.getElementById("routeCheckBox:" + routeNumber);
                                                    var routeDatesCheckBoxes = targetTable.querySelectorAll(".dates");

                                                    for (x = 0; x < routeDatesCheckBoxes.length; x++) {
                                                        if (routeDatesCheckBoxes[x].checked) {
                                                            //do nothing
                                                        } else {
                                                            routeCheckBox.checked = false;
                                                            checkRouteCheckBoxes();
                                                            return;
                                                        }
                                                    }
                                                    routeCheckBox.checked = true;
                                                    checkRouteCheckBoxes();

                                                }

                                                function checkRouteCheckBoxes() {
                                                    var targetCheckBoxes = document.querySelectorAll(".routes");
                                                    for (x = 0; x < targetCheckBoxes.length; x++) {
                                                        if (targetCheckBoxes[x].checked) {
                                                            //do nothing
                                                        } else {
                                                            mainCheckBox.checked = false;
                                                            return;
                                                        }
                                                    }
                                                    mainCheckBox.checked = true;
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
