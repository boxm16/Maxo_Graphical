<?php
require_once 'Controller_2.0/IndexController.php';
$indexController = new IndexController();
$allUploadedRoutesDates = $indexController->getAllUploadedRoutesDates();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ყველა ატვირთული მონაცემები</title>
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
            /* side bar styling */
            .sidebar-container {
                position: fixed;
                width: 20%;
                height: 100%;
                right: 0;
                overflow-x: hidden;
                overflow-y: auto;
                background: lightgreen;
                color: black;
            }

            .content-container {
                padding-top: 20px;
            }

            .sidebar-logo {
                padding: 10px 15px 10px 30px;
                font-size: 20px;
                background-color: green;
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
                color: black;
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
                background-color: green;
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
                font-size: 20px;
                text-transform: uppercase;
                background-color: lightgreen;
                padding: 10px 15px 10px 30px;
            }

            .sidebar-navigation .header::before {
                background-color: transparent;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    <nav class="navbar fixed-top navbar-light bg-light">
                        <a class="btn btn-success" href="index_2.0.php" style="font-size: 20px">საწყისი გვერდი</a>

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
                        <a class="btn btn-warning" href="uploadForm_2.0.php" style="font-size: 20px">ახალი ფაილის ატვირთვა</a>
                       
                    </nav>
                </div>
            </div><hr><hr><hr><hr>
            <div class="row">                

                <div class="col-10 align-self-start">
                    <div class="content-container">
                        <div class="container-fluid">
                            <h3>ყველა ატვირთული მონაცემები</h3>
                            <?php
                            if (count($allUploadedRoutesDates) > 1) {
                                echo "<div style=\"left:0\"><h2>აირჩიე მარშრუტი</h2></div>";
                            }
                            ?>

                            <hr>
                            <?php
                            $bigTableRowBuilder = "";
                            foreach ($allUploadedRoutesDates as $routeDate) {
                                $routeNumber = $routeDate->getRouteNumber();
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

                                    <table style="text-align:center; font-size:20px; width:40%;" id="daysOfRoute:<?php echo $routeNumber; ?>">
                                        <tbody>
                                            <?php
                                            $dates = $routeDate->getDates();
                                            foreach ($dates as $dateStamp) {

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


                                <?php
                            }
                            ?>


                            <hr>
                        </div>
                    </div>





                </div>
                <div class="col-2 align-self-start">
                    <div class="sidebar-container">
                        <div class="sidebar-logo">
                            <h3> ფუნქციები</h3>
                        </div>
                        <ul class="sidebar-navigation">
                            <li class="header">გამოთვლები</li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-cog" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-cog" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-cog" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <a href="#">
                                <i class="fa fa-cog" aria-hidden="true"></i> -----
                            </a> <li>
                                <a href="#">
                                    <i class="fa fa-users" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-cog" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-users" aria-hidden="true"></i> -----
                                </a>
                            </li>
                            <li>
                                <a href="routes.php" target="_blank">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> მარშრუტების დასახელებები
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
                                                    function selectRouteAllDates(event, routeNumber) {
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
        </script>
    </body>
</html>
