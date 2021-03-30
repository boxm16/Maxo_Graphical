<?php
require_once 'Controller/RouteDBController.php';
$routeController = new RouteDBController();
if (isset($_POST["changeRouteName"])) {
    $routeNumber = $_POST["routeNumber"];
    $aPoint = $_POST["aPoint"];
    $bPoint = $_POST["bPoint"];
    $routeController->changeRouteNames($routeNumber, $aPoint, $bPoint);
}


$routes = $routeController->getRoutePoints();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>მარშრუტების დასახელებები</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>

        </style>
    </head>

    <body>
        <div class="container">
            <div class=""row>
                <div class="col">  
                    <center><h1>მარშრუტების დასახელებები</h1></center>
                    <hr>
                    <center>

                        <table>
                            <thead>
                                <tr>
                                    <th><center>#</center></th>
                            <th><center>A პუნკტის დასახელება</center></th>
                            <th><center>B პუნკტის დასახელება</center></th>
                            <th></th>
                            </tr>
                            </thead>
                            <?php
                            $tr = "";
                            foreach ($routes as $route) {
                                $routeNumber = $route->getNumber();
                                $aPoint = $route->getAPoint();
                                $bPoint = $route->getBPoint();
                                $tr .= "<tr>"
                                        . "<td>"
                                        . "<input name=\"number\" type=\"text\" value=\"$routeNumber\" readonly size=\"3\">"
                                        . "</td>"
                                        . "<td>"
                                        . "<input name=\"aPoint\" type=\"text\" value=\"$aPoint\" size=\"50\">"
                                        . "</td>"
                                        . "<td>"
                                        . "<input name=\"bPoint\" type=\"text\" value=\"$bPoint\" size=\"50\">"
                                        . "</td>"
                                        . "<td>"
                                        . " <button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#confirmationWindow\" onclick=\"collect(event)\">შეცვალე</button>"
                                        . "</td>"
                                        . "</tr>";
                            }
                            echo $tr;
                            ?>
                        </table>
                    </center>

                    <!-- Button trigger modal -->


                    <!-- Modal -->
                    <form action="routes.php" method="POST">
                        <div class="modal fade bd-example-modal-lg" id="confirmationWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLongTitle">
                                            დაადასტურე #<input id="routeNumber" name="routeNumber" type="text" readonly size="1" > მარშრუტიის დასახელების ცვლილება

                                        </h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div> 

                                    <div class="modal-body">
                                        A პუნკტის დასახელება <br><input id="aPoint" name="aPoint"  type="text" readonly size="50" style="font-size:30px">
                                        <br>
                                        B პუნკტის დასახელება <br><input id="bPoint" name ="bPoint"  type="text" readonly size="50" style="font-size:30px">
                                        <input name="changeRouteName" hidden>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">გაუქმება</button>
                                        <button type="submit" class="btn btn-primary">დადასტურება</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end modal -->



                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            function collect(event) {

                let row = event.target.parentNode.parentNode;
                let inputs = row.querySelectorAll("input");
                for (var x = 0; x < inputs.length; x++) {
                    let name = inputs[x].name;
                    if (name == "number") {
                        routeNumber.value = inputs[x].value;
                    }
                    if (name == "aPoint") {
                        aPoint.value = inputs[x].value;
                    }
                    if (name == "bPoint") {
                        bPoint.value = inputs[x].value;
                    }
                }

            }
        </script>
    </body>
</html>
