<?php

require_once "SimpleXLSX.php";
require 'vendor/autoload.php';

class CalculationsController {

    public function countExcelFile($clientId) {
        $spreadsheet = $this->readExcelFile_2($clientId);
        $x = 10;
        while ($x < 1000) {
            echo "Cell" . $cellValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(5, $x)->getValue();
            echo "<br>";
            $x++;
        }
    }

    private function readExcelFile($clientId) {
        if ($xlsx = SimpleXLSX::parse("uploads/calculationsExcelFile" . $clientId . ".xlsx")) {
            $rows = $xlsx->rowsEx();
        } else {
            header("Location:excelFileErrorPage.php");
            echo "ფაილი არ არის ატვირთული ან დაზიანებულია(" . SimpleXLSX::parseError() . ")";
            echo "<hr>";
            return;
        }
        return $rows;
    }

    private function readExcelFile_2($clientId) {
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
