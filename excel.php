<?php
require_once 'Controller/RouteXLController.php';
if (!isset($GLOBASL["routes"])) {
    $routeController = new RouteXLController();
} else {
    $routes = $GLOBASL["routes"];
}
//starting building tables
$tableBodyBuilder = "";
$size = count($routes);
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ინტერვალები</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
            /* navbar styling */
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background-color: green;
                position: fixed;
                top: 0;
                width: 2500px;
            }

            li {
                float: left;
            }

            li a {
                display: block;
                color: white;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
            }

            li a:hover {
                background-color:white;
            }

            .active {
                background-color: lightgreen;
            }
            /* end of navbar styling */
            /* loader styling */
            .content {display:none;}
            .preload { width:100px;
                       height: 100px;
                       position: fixed;
                       top: 50%;
                       left: 50%;}
            /* end of loader styling*/


            /*table styling */
            table thead tr th {
                /* Important  for table head sticking whre it is*/
                background-color: white;
                position: sticky;
                z-index: 100;
                top: 50px;

            }
            /* other staff below */
            table, thead, tr, th, td {
                border: 2px solid black;

            }
            /* this for black line between A_B and B_A*/
            .blackLine {
                background-color:black;

            }

        </style>
    </head>
    <body>
        <?php
        include 'navBar.php';
        ?>
        
            <table  id="header-fixed" style="width:100%">
                <thead>
                    <tr>
                        <th colspan="7" style="text-align: center">A_B</th>
                        <th colspan="7" style="text-align: center">B_A</th>
                    </tr>
                    <tr>
                        <th colspan="5">საგზურზე დაყრდნობით გამოთვლები</th>
                        <th colspan="2">GPS გამოთვლები</th>
                        <th colspan="5">საგზურზე დაყრდნობით გამოთვლები</th>
                        <th colspan="2">GPS გამოთვლები</th>
                    </tr>
                    <tr>
                        <th>დაგეგმილი<br>გასვლის<br>დრო</th>
                        <th>ფაქტიური<br>გასვლის<br>დრო</th>
                        <th>დაგეგმილი<br>ინტერვალი</th>
                        <th>ფაქტიური<br>ინტერვალი</th>
                        <th>გასვლის<br>#</th>
                        <th>გასვლის<br>#</th>
                        <th>GPS<br>ინტერვალი</th>

                        <th>დაგეგმილი<br>გასვლის<br>დრო</th>
                        <th>ფაქტიური<br>გასვლის<br>დრო</th>
                        <th>დაგეგმილი<br>ინტერვალი</th>
                        <th>ფაქტიური<br>ინტერვალი</th>
                        <th>გასვლის<br>#</th>
                        <th>გასვლის<br>#</th>
                        <th>GPS<br>ინტერვალი</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($routes as $route) {
                        $days = $route->getDays();
                        foreach ($days as $day) {
                            $dateStamp = $day->getDateStamp();
                            echo "<tr><td colspan=\"14\"  style=\"text-align: center\">$dateStamp</td></tr>";
                            $tripPeriods = $day->getTripPeriods();
                            $abVTableBuilder = "";
                           

                            echo "<tr><td colspan=\"5\">Table here</td><td colspan=\"2\">GPS TABLE HERE</td><td colspan=\"7\">table here</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        //this founction is for loader spinner. alsow first scrip srs is for this spinner, whout older does not work
        $(function () {
            $(".preload").fadeOut(2000, function () {
                $(".content").fadeIn(1000);
            });
        });
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
        }

    </script>
</body>
</html>
