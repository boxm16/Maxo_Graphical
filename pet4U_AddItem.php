<?php
require_once 'Pet4U/DataBaseTools_pet4U.php';
if (isset($_POST["addItem"])) {
    $item = new Item();
    $item->setId($_POST["id"]);
    $item->setBarcode($_POST["barcode"]);
    $item->setDescription($_POST["description"]);
    $item->setNotes($_POST["notes"]);
    $dataBaseTools = new DataBaseTools_pet4U();
    $result = $dataBaseTools->saveItem($item);
    echo ";a;a;";
}
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>ADD ITEM</title>
    </head>
    <body>

        <div class="container">
            <center><h1>Add Item</h1></center>
            <form action="Pet4U/Controller.php" method="POST">
                <input hidden name="addItem">
                <h2>ID</h2>  <input name="id" class="form-control" type="text"  aria-label="default input example">

                <h2>Barcode</h2>  <input name="barcode" class="form-control" type="text"  aria-label="default input example">

                <h2>Description</h2>  <input name="description" class="form-control" type="text"  aria-label="default input example">

                <h2>Notes</h2>  <input name="notes" class="form-control" type="text"  aria-label="default input example">
                <hr>
                <button type="submit" class="btn btn-primary mb-3">Save</button>
            </form>
        </div>

        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        -->
    </body>
</html>