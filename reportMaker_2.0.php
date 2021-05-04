<?php
require_once 'Controller_2.0/CronJobController.php';
require_once 'Controller_2.0/IndexController.php';

$cronJobController = new CronJobController();
$isLoading = $cronJobController->getLoadingStatus();
if ($isLoading) {
    $lastUploadedRoutesDates = array();
} else {
    $indexController = new IndexController();
    $lastUploadedRoutesDates = $indexController->getLastUploadedRoutesDates();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>რეპორტების მომზადება</title>
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


            /* side bar styling */
            .sidebar-container {
                position: fixed;
                width: 400px;
                height: 100%;
                right: 0;
                overflow-x: hidden;
                overflow-y: auto;
                background: white;
                color: black;
            }

            .content-container {
                padding-top: 20px;
            }

            .sidebar-logo {
                padding: 10px 15px 10px 30px;
                font-size: 20px;
                background-color: yellow;
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
                background-color: yellow;
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
                background-color: lightyellow;
                padding: 10px 15px 10px 30px;
            }

            .sidebar-navigation .header::before {
                background-color: transparent;
            }

            .content-container {
                padding-left: 0;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    <nav class="navbar fixed-top navbar-light bg-light">
                        <div style="padding-left:  20%">
                            <table >
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
                        </div>
                    </nav>
                </div>
            </div><hr><hr><hr><hr>
            <div class="row">                

                <div class="col-10 align-self-start">
                    <div class="content-container">
                        <div class="container-fluid">
                            <h3>ბოლო ატვირთული მონაცემები</h3>
                            <div id="loadingStatusDisplay"><?php
                                if ($isLoading) {
                                    echo "მიმდინარეობს ატვირთული ფაილების სტატუსის დადგენა";
                                }
                                ?>
                            </div>
                            <?php
                            if (count($lastUploadedRoutesDates) > 1) {
                                echo "<div style=\"left:0\"><h2>აირჩიე მარშრუტი</h2></div>";
                            }
                            ?>

                            <hr>
                            <?php
                            $bigTableRowBuilder = "";
                            foreach ($lastUploadedRoutesDates as $routeDate) {
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
                <form  id="form" action="deletion.php" method="POST">
                    <div class="col-2 align-self-start">
                        <div class="sidebar-container">
                            <div class="sidebar-logo">
                                <h3>მონიშნე სასურველი რეპორტი</h3>
                            </div>
                            <ul class="sidebar-navigation">

                                <li>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="routeDetailsReport">
                                                </td>
                                                <td>
                                                    <i class="fa fa-home" aria-hidden="true"></i>ბრუნები
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="intervalsReport">
                                                </td>
                                                <td>
                                                    <i class="fa fa-home" aria-hidden="true"></i>ინტერვალები
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </li>
                                <li>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="excelFormReport">
                                                </td>
                                                <td>
                                                    <i class="fa fa-home" aria-hidden="true"></i>ექსელის ფორმა
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="number" style="width:60px" value="40" name="excelFormPercentage">
                                                </td>
                                                <td>
                                                    <i class="fa fa-home" aria-hidden="true"></i>%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>  
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

                                    <button class="btn btn-primary" type="button " onclick="submitLink('reports_2.0.php')" >
                                        რეპორტების შექმნა
                                    </button>

                                </li>

                            </ul>
                        </div>
                    </div>
                    <input hidden type="text" id="routes_dates" name="routes:dates">
                </form>
            </div>
        </div>


    </form>
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
                                        //this is to turn a link  into submit button
                                        function submitLink(target) {

                                            form.target = "_blank";
                                            form.action = target;
                                            routes_dates.value = collectSellectedCheckBoxes();
                                            form.submit();
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
