<?php

require_once 'DataBaseTools_pet4U.php';

if (isset($_POST["addItem"])) {
    $item = new Item();
    $item->setId($_POST["id"]);
    $item->setBarcode($_POST["barcode"]);
    $item->setDescription($_POST["description"]);
    $item->setNotes($_POST["notes"]);
    $dataBaseTools = new DataBaseTools_pet4U();
    $saveItem = $dataBaseTools->saveItem($item);
    echo ";a;a;";
    header("Location:../pet4U_AddItem.php");
}
