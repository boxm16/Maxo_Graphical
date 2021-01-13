<?php
require_once './Controller/RouteXLController.php';
if (isset($_GET["routeNumber"]) && isset($_GET["dateStamp"]) && isset($_GET["exodusNumber"])) {
    $routeNumber = $_GET["routeNumber"];
    $dateStamp = $_GET["dateStamp"];
    $exodusNumber = $_GET["exodusNumber"];
    $startTimeScheduled = $_GET["startTimeScheduled"];
    $exodusDetails = "$dateStamp,  მარშრუტი # $routeNumber, გასვლა #$exodusNumber";
    $routeController = new RouteXLController();
    $routes = $routeController->getRoutes();
} else {
    $message = "AQ RM MOGIYVANA SHE DALOCVILO?";
}
if (!isset($GLOBASL["routes"])) {
    $routeController = new RouteXLController();
    $routes = $GLOBALS["routes"];
} else {
    $routes = $GLOBALS["routes"];
}
foreach ($routes as $route) {

    $days = $route->getDays();

    foreach ($days as $day) {
        $exoduses = $day->getExoduses();
        foreach ($exoduses as $exodus) {

            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {

                foreach ($tripPeriods as $tripPeriod) {
                    
                }
            }
        }
    }
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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


        </style>
    </head>
    <body>
        <div style="background-color:green;color:white"> <center><h2> <?php echo $exodusDetails; ?> </h2></center></div>

        <table style="width:100%">
            <thead>

                <tr>
                    <th>გეგმიუირი<br>გასვლის<br>დრო</th>
                    <th>ფაქტიური<br>გასვლის<br>დრო</th>
                    <th>სხვაობა</th>
                    <th>------</th>
                    <th>გეგმიუირი<br>მისვლის<br>დრო</th>
                    <th>ფაქტიური<br>მისვლის<br>დრო</th>
                    <th>სხვაობა</th>
                    <th></th>
                    <th>წირის<br>გეგმიური<br>დრო</th>
                    <th>წირის<br>ფაქტიური<br>დრო</th>
                    <th>დგომის<br>გეგმიური<br> დრო</th>
                    <th>დგომის<br>ფაქტიური<br>დრო</th>
                    <th>'დაკარგული<br>დრო'</th>
                </tr>
            </thead>
            <tbody>              
                <tr><td>Bali</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td></td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td></tr>

                <?php
                for ($x = 0; $x < 40; $x++) {
                    echo "                <tr><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td></td><td>akako</td><td>akako</td><td>akako</td><td>akako</td><td>akako</td></tr>
   ";
                }
                ?>         </tbody>
        </table>
        <?php
        echo "<br><br><br>";
        echo $exodusDetails;
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            //this code is for adding row clicking listener
            var chosenRow = null
            var cells = document.querySelectorAll("tr");

            for (var cell of cells) {
                cell.addEventListener('click', marker)
            }

            function marker(event) {
                var row = event.target.parentNode;
                if (chosenRow != null) {
                    chosenRow.style.fontWeight = "normal";
                }
                row.style.fontWeight = "bold";
                chosenRow = row;
            }</script>
    </body>
</html>
