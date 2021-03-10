<?php
require_once 'clientId.php';

$errorAlert = "";
$errorMessage = "";
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {//first checking if request commming form submit or it is empty request
    //if it is an empty reques, now need for php here
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));



    if ($_FILES["fileToUpload"]["size"] > 500000000) {
        $errorMessage = "ფაილის ზომა დაშვებულზე დიდია (მაგას რასაც ტვირთავ ექსელის ფაილი არ იქნება, რაია მაგხელა??? ).";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($fileType != "xlsx") {
        $errorMessage = "დაშვებული მხოლოდ xlsx ფორმატის ფაილებია";
        $uploadOk = 0;
    }
    if ($_FILES['fileToUpload']['size'] == 0) {
        $errorMessage = "არცერთი ფაილი არ იყო არჩეული";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $errorAlert = "ფაილის ატვირთვა ვერ მოხერხდა. სცადე თავიდან";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "uploads/routeExcelFile_" . $clientId . ".xlsx")) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            deployFile();
        } else {

            $errorAlert = "ფაილის ატვირთვა ვერ მოხერხდა. სცადე თავიდან";
        }
    }
}

function deployFile() {
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ფაილის ატვირთვა</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            tr {
                height: 80px;
            }
            /* navbar styling */
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background-color: green;
                position: fixed;
                top: 0;
                width: 100%;
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
        </style>
    </head>
    <body>

        <?php
        include 'navBar.php';
        ?>
        <div class="container">
            <div class="row">
                <div class="col">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <center>
                            <table >
                                <tr>
                                    <td style="color:red">
                                <center><h2><?php echo $errorAlert; ?></h2></center>
                                </td>
                                </tr>
                                <tr>
                                    <td style="color:red">
                                <center><h2>  <?php echo $errorMessage ?></h2></center>
                                </td>
                                </tr>
                                <tr>
                                    <td>
                                <center><h1>აირჩიე ასატვირთი ფაილი</h1></center>

                                </td>
                                </tr>
                                <tr>
                                    <td>
                                <center> <input  form-control-file type="file" name="fileToUpload" id="fileToUpload"></center>
                                </td>
                                </tr> 
                                <tr>
                                    <td>
                                <center><input class="btn btn-lg btn-primary" type="submit" value="ატვირთვა" name="submit"></center>  
                                </td>
                                </tr>
                            </table>
                        </center>


                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </body>
</html>
