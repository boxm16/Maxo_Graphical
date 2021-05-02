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
        <meta charset="UTF-8">
        <title>საწყისი გვერდი v_3.0</title>
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
                background: lightblue;
                color: black;
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
                font-size: 20px;
                text-transform: uppercase;
                background-color: lightblue;
                padding: 10px 15px 10px 30px;
            }

            .sidebar-navigation .header::before {
                background-color: transparent;
            }
        </style>

        <script>
<?php
if ($isLoading) {
    echo "
            var myTaskScheduler = setInterval(getLoadingStatus, 1000);
            function getLoadingStatus() {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        let loadingStatusDisplay = document.getElementById(\"loadingStatusDisplay\");
                        if (this.responseText == \"loading\") {
                            loadingStatusDisplay.innerHTML = \"მიმდინარეობს ატვირთული ფაილის მონაცემთა ბაზაში გადატანა\"
                            loadingStatusDisplay.style.color = \"#ff0000\";
                        } else {
                            loadingStatusDisplay.innerHTML = \"ფაილის მონაცემთა ბაზაში გადატანა დასრილებულია\"
                            loadingStatusDisplay.style.color = \"green\";
                            location.reload();
                        }

                    }
                };
                xmlhttp.open(\"GET\", \"cronJobDispatcher.php?loadingStatusRequest=on\", true);
                xmlhttp.send();

            } ";
}
?>
        </script>  
    </head>
    <body>  
        <div class="container">
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

    </body>
</html>
