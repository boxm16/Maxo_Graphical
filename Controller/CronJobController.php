<?php

require_once "SimpleXLSX.php";
require 'vendor/autoload.php';
require_once 'DAO/DataBaseTools.php';

class CronJobController {

    private $dataBaseTools;

    function __construct() {
        $this->dataBaseTools = new DataBaseTools();
    }

    public function isLoading(): bool {
        return $this->dataBaseTools->isLoading();
    }

    public function registerNewUpload() {
        $this->dataBaseTools->registerNewUpload();
    }

    public function readRows(int $startRow, int $endRow) {
        
    }

    public function countExcelFile($clientId) {
        $spreadsheet = $this->readExcelFile($clientId);
        $x = 10;
        while ($x < 1000) {
            echo "Cell" . $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(5, $x)->getValue();
            echo "<br>";
            $x++;
        }
    }

    private function readExcelFile($clientId) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadFilter(new MyReadFilter());
        $spreadsheet = $reader->load("uploads/calculationsExcelFile" . $clientId . ".xlsx");

        return $spreadsheet;

        /*
          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
          $reader->setReadDataOnly(true);
          $spreadsheet = $reader->load("uploads/calculationsExcelFile" . $clientId . ".xlsx");
         */
    }

}

class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

    public function readCell($column, $row, $worksheetName = '') {
        // Read title row and rows 20 - 30
        if ($row == 1 || ($row >= 1 && $row <= 10000)) {
            return true;
        }
        return false;
    }

}
