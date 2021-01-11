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
        <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
        <div class="content">
            <table  id="header-fixed" style="width:100%">
                <thead>
                    <tr>
                        <th style='text-align: center'>A_B</th>
                        <th style='text-align: center'>B_A</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <thead>
                                <th style='text-align: center'>საგზურზე დაყრდნობით გამოთვლები</th>
                                <th style='text-align: center'>GPS გამოთვლები</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th style='text-align: center'>გეგმიუირი<br>გასვლა</th> 
                                                        <th style='text-align: center'>ფაქტიური<br>გასვლა</th> 
                                                        <th style='text-align: center'>გეგმიუირი<br>ინტერვალი</th> 
                                                        <th style='text-align: center'>ფაქტიური<br>ინტერვალი</th>
                                                        <th style='text-align: center'>გასვლის<br>#</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($x = 0; $x < 30; $x++) {
                                                        echo "<tr>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="vertical-align: top">
                                            <table>
                                                <thead>
                                                <th style='text-align: center'>გასვლის<br>#</th> 
                                                <th style='text-align: center'>GPS<br>ინტერვალი</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($x = 0; $x < 20; $x++) {
                                                        echo "<tr>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table>
                                <thead>
                                <th style='text-align: center'>საგზურზე დაყრდნობით გამოთვლები</th>
                                <th style='text-align: center'>GPS გამოთვლები</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th style='text-align: center'>გეგმიუირი<br>გასვლა</th> 
                                                        <th style='text-align: center'>ფაქტიური<br>გასვლა</th> 
                                                        <th style='text-align: center'>გეგმიუირი<br>ინტერვალი</th> 
                                                        <th style='text-align: center'>ფაქტიური<br>ინტერვალი</th>
                                                        <th style='text-align: center'>გასვლის<br>#</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($x = 0; $x < 30; $x++) {
                                                        echo "<tr>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="vertical-align: top">
                                            <table>
                                                <thead>
                                                <th style='text-align: center'>გასვლის<br>#</th> 
                                                <th style='text-align: center'>GPS<br>ინტერვალი</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($x = 0; $x < 20; $x++) {
                                                        echo "<tr>"
                                                        . "<td>TEXT</td>"
                                                        . "<td>TEXT</td>"
                                                        . "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
