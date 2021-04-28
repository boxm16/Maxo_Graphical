<?php

require_once "SimpleXLSX.php";
require 'vendor/autoload.php';
require_once 'DAO/DataBaseTools.php';
//---------
require_once 'LoadModel/Chunk.php';
require_once 'LoadModel/TripVoucher.php';
require_once 'LoadModel/TripPeriod.php';

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
        $nextChunkStartingRow = $this->dataBaseTools->getStartRowIndex();
        $chunkMaxLength = 1000;
        $nextChunkLastRow = $nextChunkStartingRow + $chunkMaxLength;
        $spreadsheet = $this->readExcelFile($clientId, $nextChunkStartingRow, $nextChunkLastRow);
        $nextChunkEndRow = $this->getNextChunkEndRow($spreadsheet, $nextChunkLastRow);
        $nextChunk = $this->getNextChunk($spreadsheet, $nextChunkStartingRow, $nextChunkEndRow);
        if ($this->lastChunk) {
            $this->dataBaseTools->loadLastChunk();
            $this->dataBaseTools->resetTechTable();
            echo "End of Loading";
            echo "<br>";
        } else {
            $this->dataBaseTools->loadNextChunk();
            $this->dataBaseTools->registerNextChunk($nextChunkEndRow);
        }
    }

    private function getNextChunkEndRow($spreadsheet, $lastRow) {

        $lastVoucherInChunk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $lastRow)->getValue();
        if ($lastVoucherInChunk == "") {
            $this->lastChunk = true;
        }

        while (true) {
            $lastRow--;
            $beforeLastVoucherInChunk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $lastRow)->getValue();
            if ($beforeLastVoucherInChunk != $lastVoucherInChunk) {
                return $lastRow + 1;
            }
            if ($lastRow == 8) {
                return 1000;
            }
        }
        return $lastRow;
    }

    public function registerNewUpload() {
        $this->dataBaseTools->registerNewUpload();
    }

    public function getNextChunk($spreadsheet, int $startRow, int $endRow) {
        $chunk = new Chunk();
        $x = $startRow;
        while ($x < $endRow) {
            $routeNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $x)->getValue();

            if ($routeNumber == "") {
                $this->lastChunk = true;

                break;
            } else {
//here is actual reading of spreadsheet rows and sending values to apropriate destination
// echo $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $x) . "---" . $spreadsheet->getActiveSheet()->getCellByColumnAndRow(17, $x) . "<br>";

                $routes = $chunk->getRoutes();
                if (in_array($routeNumber, $routes)) {
//do nothing
                } else {
                    array_push($routes, $routeNumber);
                }
                $chunk->setRoutes($routes);
                $chunk = $this->getTripVoucherData($chunk, $spreadsheet, $x);
                $chunk = $this->getTripPeriodData($chunk, $spreadsheet, $x);
                $x++;
            }
        }
    }

    private function getTripPeriodData(Chunk $chunk, $spreadsheet, $x) {
        return $chunk;
    }

    private function getTripVoucherData(Chunk $chunk, $spreadsheet, $x) {
        $tripVoucherNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $x)->getValue();
        $tripVouchers = $chunk->getTripVouchers();

        if (array_key_exists($tripVoucherNumber, $tripVouchers)) {
            //do nothing
        } else {


            $routeNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $x)->getValue();
            $dateStamp = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(6, $x)->getValue();
            $exodusNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $x)->getValue();
            $driverNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(4, $x)->getValue();
            $driverName = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(5, $x)->getValue();
            $busNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(3, $x)->getValue();
            $busType = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(2, $x)->getValue();
            $notes = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(29, $x)->getValue();
            $tripVoucher = new TripVoucher();
            $tripVoucher->setNumber($tripVoucherNumber);
            $tripVoucher->setRouteNumber($routeNumber);
            $tripVoucher->setDateStamp($dateStamp);
            $tripVoucher->setExodusNumber($exodusNumber);
            $tripVoucher->setDriverName($driverName);
            $tripVoucher->setDriverNumber($driverNumber);
            $tripVoucher->setBusNumber($busNumber);
            $tripVoucher->setBusType($busType);
            $tripVoucher->setNotes($notes);
            $tripVouchers[$tripVoucherNumber] = $tripVoucher;
            $chunk->setTripVouchers($tripVouchers);
        }
        return $chunk;
    }

    private function readExcelFile($clientId, $startRow, $endRow) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadFilter(new MyReadFilter($startRow, $endRow));
        $spreadsheet = $reader->load("uploads/calculationsExcelFile" . $clientId . ".xlsx");

        return $spreadsheet;
    }

}

//--------------//---------------//----------------

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
