<?php

require_once "SimpleXLSX.php";
require 'vendor/autoload.php';
require_once 'DAO/DataBaseTools.php';
//---------
require_once 'LoadModel/Route.php';
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
        $nextChunk = $this->readNextChunk($spreadsheet, $nextChunkStartingRow, $nextChunkEndRow);
        if ($this->lastChunk) {
            $this->dataBaseTools->loadLastChunk();
            $this->dataBaseTools->resetTechTable();
        } else {
            $this->dataBaseTools->loadNextChunk();
            $this->dataBaseTools->registerNextChunk($nextChunkEndRow);
        }
    }

    private function getNextChunkEndRow($spreadsheet, $lastRow) {

        $lastVoucherInChunk = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $lastRow)->getValue();

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

    public function readNextChunk($spreadsheet, int $startRow, int $endRow) {
        $routes = array();
        $x = $startRow;
        while ($x < $endRow) {
            $routeNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $x)->getValue();

            if ($routeNumber == "") {
                $this->lastChunk = true;
                echo "the end";
                echo "<br>";
                break;
            } else {
                //here is actual reading of spreadsheet rows and sending values to apropriate destination
                // echo $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $x) . "---" . $spreadsheet->getActiveSheet()->getCellByColumnAndRow(17, $x) . "<br>";


                if (array_key_exists($routeNumber, $routes)) {
                    $existingRoute = $routes[$routeNumber];
                    $refilledRoute = $this->addRowElementsToRoute($existingRoute, $spreadsheet, $x);
                    $routes[$routeNumber] = $refilledRoute;
                } else {
                    $newRoute = new Route();
                    $newRoute->setNumber($routeNumber);
                    $refilledRoute = $this->addRowElementsToRoute($newRoute, $spreadsheet, $x);
                    $routes[$routeNumber] = $refilledRoute;
                }

                $x++;
            }
        }
    }

    private function addRowElementsToRoute($route, $spreadsheet, $x) {
        $tripVoucherNumber = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $x)->getValue();


        //echo $tripVoucherNumber;
        // echo "<br>";
        return $route;
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
