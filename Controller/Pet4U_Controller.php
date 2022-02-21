<?php

require_once 'DAO/DataBaseTools_Pet4U.php';

class Pet4U_Controller {

    public function saveItem($item) {
        $dataBaseTools = new DataBaseTools_pet4U();
        return $dataBaseTools->saveItem($item);
    }

}
