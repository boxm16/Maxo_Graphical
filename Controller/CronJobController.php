<?php

require_once "SimpleXLSX.php";
require 'vendor/autoload.php';
require_once 'DAO/DataBaseTools.php';

class CronJobController {

    private $lastChunk;
    private $dataBaseTools;

    function __construct() {
        $this->lastChunk = false;
        $this->dataBaseTools = new DataBaseTools();
    }

    public function isLoading(): bool {
        $inLoadingMode = $this->dataBaseTools->isLoading();
        if ($inLoadingMode) {
            $this->loadChunk();
        }
        return $inLoadingMode;
    }

    public function loadChunk() {
        $clientId = 111;
        $nextChunkStartingRow = $this->dataBaseTools->getLastUploadedRowIndex();
        $nextChunk = $this->readNextChunk($clientId, $nextChunkStartingRow, $nextChunkStartingRow + 1000);
        if ($this->lastChunk) {
            $this->dataBaseTools->loadLastChunk();
            $this->dataBaseTools->resetTechTable();
        } else {
            $this->dataBaseTools->loadNextChunk();
            $this->dataBaseTools->registerNextChunk($nextChunkStartingRow, $nextChunkStartingRow + 1000);
        }
    }

    public function registerNewUpload() {
        $this->dataBaseTools->registerNewUpload();
    }

    public function readNextChunk($clientId, int $startRow, int $endRow) {
        $spreadsheet = $this->readExcelFile($clientId, $startRow, $endRow);
        $x = $startRow;
        while ($x < $endRow) {
            if ($spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $x) == "") {
                $this->lastChunk = true;
                echo "the end";
                break;
            }
            echo $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $x) . "---" . $spreadsheet->getActiveSheet()->getCellByColumnAndRow(17, $x) . "<br>";
            $x++;
        }
    }

    private function readExcelFile($clientId, $startRow, $endRow) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadFilter(new MyReadFilter($startRow, $endRow));
        $spreadsheet = $reader->load("uploads/calculationsExcelFile" . $clientId . ".xlsx");

        return $spreadsheet;
    }

}

class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

    private $startRow;
    private $endRow;

    function __construct($startRow, $endRow) {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell($column, $row, $worksheetName = '') {
        // Read  rows startRow - endRow
        if ($row >= $this->startRow && $row <= $this->endRow) {
            return true;
        }
        return false;
    }

}


//--------MODEL ---------//
