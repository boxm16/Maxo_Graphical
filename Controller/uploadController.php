<?php

$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    if ($_FILES["fileToUpload"]["size"] > 500000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($fileType != "xlsx") {
        echo "Sorry, only xlsx files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.<br>რაცხა არ მოეწონა";
        echo " <hr><br><br><a href='../uploadForm.php'>ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href='../index.php' target='_blank'>მთავარ გვერძე დაბრნუნება</a>";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../uploads/excellFile.xlsx")) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            deployFile();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

function deployFile() {
    header("Location:../excel.php");
}

?>