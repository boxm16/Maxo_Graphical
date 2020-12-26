<!DOCTYPE html>
<html>
    <body>
             <a href="excel.php">უკან დაბრუნება</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>

             <hr><br><br>
             <form action="Controller/uploadController.php" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" name="submit">
        </form>

    </body>
</html>
