
<!DOCTYPE html>

<html>
    <head>

        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>
    <body>
    <hr><center><h2>ჩაწერე ფაილის დასახელება და დააჭირე 'ექსელში ექსპორტი'</h2><br>
        <h6>დასახელებაში არ გამოიყენო ნიშნები: / , \ , "</h6>
    </center>
    <hr>
    <form  action="guaranteedExport.php" method="POST">
        <center><input type="text" name="fileName" size="70"></center>
        <hr>
        <input hidden name="guaranteedClear">
        <center><button type="submit" class="btn btn-warning ">ექსელში ექსპორტი</button></center>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
