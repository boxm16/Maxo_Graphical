<?php
require_once 'Controller/RouteXLController.php';
if (!isset($GLOBASL["routes"])) {
    $routeController = new RouteXLController();
} else {
    $routes = $GLOBASL["routes"];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ბრუნები დეტალურად</title>
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

            table {
                /* Not required only for visualizing */
                border-collapse: collapse;
                width: 100%;
            }

            table thead tr th {
                /* Important  for table head sticking whre it is*/
                background-color: white;
                position: sticky;
                z-index: 100;
                top: 50px;
            }

           
        </style>
    </head>
    <body>
        <?php
        include 'navBar.php';
        ?>
        <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content">
            <table id="header-fixed">
                <thead>
                    <tr>
                        <th>Header 1</th>
                        <th>Header 2</th>
                        <th>Header 3</th>
                    </tr>
                </thead>
                <tbody>
                <tbody>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <th>Text</th>
                        <th>Text</th>
                        <th>Text</th>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Text</td>
                        <td>Text</td>
                    </tr>
                </tbody>

            </table>
        </div>

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


        </script>
    </body>
</html>
