<?php

require_once 'DAO/DataBaseTools_Pet4U.php';

class Pet4U_Controller {

    public function saveItem($item) {
        $dataBaseTools = new DataBaseTools_pet4U();
        return $dataBaseTools->saveItem($item);
    }

    public function getAllItems() {
        $dataBaseTools = new DataBaseTools_pet4U();
        return $dataBaseTools->getAllItems();
    }

    public function getAllItemsStringed() {
        $dataBaseTools = new DataBaseTools_pet4U();
        $itemsStringed = array();
        $items = $dataBaseTools->getAllItems();
        foreach ($items as $item) {
            $stringedItem = $item->getId() . ":" . $item->getDescription();
            $itemsStringed[$item->getBarcode()] = $stringedItem;
        }
        return $itemsStringed;
    }

}
