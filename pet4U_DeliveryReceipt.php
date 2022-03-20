<?php
require_once 'Controller/Pet4U_Controller.php';
$controller = new Pet4U_Controller();
$items = $controller->getAllItems();
$itemsStringed = $controller->getAllItemsStringed();
?>
<!DOCTYPE html>

<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Delivery Receipt</title>
        <style>
            td, tr, th  {
                border:solid black 1px;

            }
        </style>
    </head>
    <body>
        <div class="container">
            <center><h1>Delivery Receipt</h1></center>
            <hr>
            <h2>Barcode</h2>  <input name="barcodereader" id="barcodereader" class="form-control" type="text"  aria-label="default input example">
            <hr>
            <table id="mainTable">
                <tbody>
                <thead>
                    <tr>
                        <th style="width:200px">BARCODE</th>
                        <th style="width:100px">ID</th>
                        <th style="width:600px">ΠΕΡΙΓΡΑΦΗ</th>
                        <th style="width:100px">ΠΟΣΟΤΗΤΑ<br>ΤΙΜΟΛΟΓΙΟΥ</th>
                        <th style="width:100px"> ΠΟΣΟΤΗΤΑ<br>ΠΑΡΑΛΑΒΗΣ</th>
                    </tr>
                </thead>
                <tr>
                    <td>1</td>
                    <td>1111</td>
                    <td>ΞΗΡΗ ΤΡΟΦΗ ΓΑΤΑΣ</td>
                    <td><input id="1t" type="text" value="5" "></td>
                    <td><input id="1" type="text" value="0" onchange="colorFunction(this)"></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>2222</td>
                    <td>ΞΗΡΗ ΤΡΟΦΗ ΣΚΥΛΟΥ</td>
                    <td><input id="2t" type="text" value="3" "></td>
                    <td><input id="2" type="text" value="0" onchange="colorFunction(this)"></td>
                </tr>
                </tbody>
            </table>

        </div>


        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        -->

        <script>
                        var $items = <?php echo json_encode($itemsStringed) ?>;// don't use quotes





                        var barcodereader = document.getElementById("barcodereader");
                        barcodereader.addEventListener("keyup", function (event) {

                            if (event.keyCode === 13) {
                                //αν δεν υπαρχει ρποιον στο τιμολογιο, create new row

                                let barcode = barcodereader.value;
                                if (barcode == "") {
                                    return;
                                }
                                let targetItem = document.getElementById(barcode);
                                if (targetItem == null) {
                                    addRows(barcodereader);
                                } else {
                                    let previousValue = targetItem.value;
                                    targetItem.value = previousValue * 1 + 1;
                                    colorFunction(targetItem);
                                }

                            }
                            // Cancel the default action, if needed
                            //  event.preventDefault();
                            // Trigger the button element with a click
                            //document.getElementById("myBtn").click();
                        });

                        function addRows(barcodereader) {
                            var table = document.getElementById('mainTable');
                            //   var tbodyRowCount = table.tBodies[0].rows.length;
                            var totalRowCount = table.rows.length;
                            let  row = table.insertRow(totalRowCount);
                            let cell1 = row.insertCell(0);
                            let cell2 = row.insertCell(1);
                            let cell3 = row.insertCell(2);
                            let cell4 = row.insertCell(3);
                            let cell5 = row.insertCell(4);
                            let barcode = barcodereader.value;


                            cell1.innerHTML = barcode;
                            cell2.innerHTML = "";
                            cell3.innerHTML = "";
                            cell4.innerHTML = "0";
                            cell5.innerHTML = "<input id='" + barcode + "' type='text' value='1'>";
                        }

                        function colorFunction(target) {
                            let itemId = target.id + "t";

                            let timologioCount = document.getElementById(itemId).value;
                            if (timologioCount == target.value) {
                                target.style.backgroundColor = "green";
                            } else {
                                if ((timologioCount * 1) < (target.value * 1)) {
                                    target.style.backgroundColor = "yellow";
                                } else {
                                    target.style.backgroundColor = "red";
                                }
                            }


                        }
        </script>
    </body>
</html>
