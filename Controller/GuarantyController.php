<?php

require 'vendor/autoload.php';
require_once 'DAO/DataBaseTools.php';

require_once "SimpleXLSX.php";
require_once 'Model/RouteGuaranteed.php';
require_once 'TimeCalculator.php';
require_once 'ExcelExportController.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuarantyController {

    private $s;
    private $dataBaseTooles;

    function __construct() {
        $this->dataBaseTooles = new DataBaseTools();
    }

    public function getGuarantyRoutes() {
        $this->s = microtime(true);

        $excelRows = $this->readExcelFile();
        $guarantyRoutes = $this->getGuarantyRoutesFromExcelRows($excelRows); //array of routes
        $routesFromDB = $this->dataBaseTooles->getRoutesNamesAndSchemes();
        $guarantyRoutesCalculated = $this->calculateGuarantyRoutesData($guarantyRoutes, $routesFromDB);
        $this->exportGuarantyRoutes($guarantyRoutesCalculated);

        return $guarantyRoutes;
    }

    private function readExcelFile() {
        if ($xlsx = SimpleXLSX::parse("uploads/guaranteedExcelFile.xlsx")) {
            $rows = $xlsx->rowsEx();
        } else {
            header("Location:excelFileErrorPage.php");
            echo "ფაილი არ არის ატვირთული ან დაზიანებულია(" . SimpleXLSX::parseError() . ")";
            echo "<hr>";
            return;
        }
        return $rows;
    }

    private function getGuarantyRoutesFromExcelRows($excelRows) {
        $routes = array();
        foreach ($excelRows as $row) {
// sifting off empty rows and rows that show something else
            if ($row[7]["value"] == "მარშრუტი" || $row[7]["value"] == "კონდუქტორი" || $row[7]["value"] == "") {

                continue;
            }

//here i put security mechnism, if the file is not the file for guarnatees

            $startTimeActual = $row[13]["value"];
            if ($startTimeActual != "") {
                echo "ატვირთული ფაილი არ არის საგარანტიო გასცლების გამოსათვლელად გამოსადეგი (ფაილში იძებნება ფაქტიური გასვლის დრო, რაც აქ დაუშვებელია)";
                echo "<a href='index.php'>დაბრუნდი უკან  და ატვირთე შესაბამისი ფაილი</a>";
                exit;
            }

            $routeNumber = $row[7]["value"];
            if (array_key_exists($routeNumber, $routes)) {
                $existingRoute = $routes[$routeNumber];
                $refilledRoute = $this->addRowElementsToRoute($existingRoute, $row);
                $routes[$routeNumber] = $refilledRoute;
            } else {
                $baseNumber = $row[0]["value"];
                $busType = $row[1]["value"];
                $newRoute = new RouteGuaranteed();
                $newRoute->setNumber($routeNumber);
                $newRoute->setBaseNumber($baseNumber);
                $newRoute->setBusType($busType);
                $refilledRoute = $this->addRowElementsToRoute($newRoute, $row);
                $routes[$routeNumber] = $refilledRoute;
            }
        }
// $routes = $this->setTripPeriodDNAs($routes);

        return $routes;
    }

    private function addRowElementsToRoute($route, $row) {
        $dateStamp_1 = $row[5]["value"];
        $dateStamp_2 = $route->getDateStamp();

        if ($dateStamp_2 == null) {
            $route->setDateStamp($dateStamp_1);
        } else {
            if ($dateStamp_1 == $dateStamp_2) {
                $route = $this->addElementsToExistingDay($route, $row);
            } else {
                echo "ატვირთული ფაილი არ არის საგარანტიო გასცლების გამოსათვლელად გამოსადეგი (ფაილში იძებნება სხვადასხვა რიცხვი, რაც აქ დაუშვებელია)";
                echo "<a href='index.php'>დაბრუნდი უკან  და ატვირთე შესაბამისი ფაილი</a>";
                exit;
            }
        }

        return $route;
    }

    private function addElementsToExistingDay($route, $row) {
        $exodusNumber = $row[8]["value"];
        $exoduses = $route->getExoduses();
        if (array_key_exists($exodusNumber, $exoduses)) {
            $existingExodus = $exoduses[$exodusNumber];
            $refilledExodus = $this->addElementsToExistingExodus($existingExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        } else {
            $newExodus = new ExodusGuaranteed();
            $newExodus->setNumber($exodusNumber);
            $refilledExodus = $this->addElementsToExistingExodus($newExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        }
        $route->setExoduses($exoduses);
        return $route;
    }

    private function addElementsToExistingExodus($exodus, $row) {

        $tripPeriods = $exodus->getTripPeriods();
        $tripPeriodTypeStampInRowCell = $row[15]["value"];
        $tripPeriodType = $this->getTripTypeFromTripStampInRowCell($tripPeriodTypeStampInRowCell);
        if ($tripPeriodType == "baseLeaving") {
            $tripPeriod = $this->createBaseLeavingPeriod($row);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "baseReturn") {
            $tripPeriod = $this->createBaseReturnPeriod($row);
//  $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "break") {
            /* do nothing
             * 
              $tripPeriod = $this->createBreakPeriod($row);
              $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
              array_push($tripPeriods, $tripPeriod);
             *
             */
        }
        if ($tripPeriodType == "round") {
            $tripPeriodsOfRound = $this->createTripPeridsOfRound($row);
            foreach ($tripPeriodsOfRound as $tripPeriod) {
// $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
                array_push($tripPeriods, $tripPeriod);
            }
        }

        $exodus->setTripPeriods($tripPeriods);
        return $exodus;
    }

    private function createBaseLeavingPeriod($row) {

        if ($row[16]["value"] != "") {
            $tripPeriodType = "baseLeaving_A";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriodType = "baseLeaving_B";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBaseReturnPeriod($row) {

        if ($row[16]["value"] != "") {
            $tripPeriodType = "A_baseReturn";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriodType = "B_baseReturn";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBreakPeriod($row) {
        $tripPeriodType = "break";
        if ($row[16]["value"] != "") {
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createTripPeridsOfRound($row) {
        $tripPeriodsOfRound = array();
        if ($row[16]["value"] != "" && $row[22]["value"] != "") {
            $leftSideTime = $this->time24($row[16]["value"]);
            $rightSideTime = $this->time24($row[22]["value"]);
            $timeCalculator = new TimeCalculator();
            $leftSideTimeInSeconds = $timeCalculator->getSecondsFromTimeStamp($leftSideTime);
            $rightSideTimeInSeconds = $timeCalculator->getSecondsFromTimeStamp($rightSideTime);
            if ($leftSideTimeInSeconds < $rightSideTimeInSeconds) {
                $tripPeriodType = "ab";
                $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                $tripPeriodType = "ba";
                $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                return $tripPeriodsOfRound;
            } else {

                $tripPeriodType = "ba";
                $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                $tripPeriodType = "ab";
                $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                return $tripPeriodsOfRound;
            }
        }
        if ($row[16]["value"] != "") {
            $tripPeriodType = "ab";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        if ($row[22]["value"] != "") {
            $tripPeriodType = "ba";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        return $tripPeriodsOfRound;
    }

    private function getTripTypeFromTripStampInRowCell($tripPeriodTypeStampInRowCell) {
//baseLeaving, baseReturn, ab, ba, break

        if (strpos($tripPeriodTypeStampInRowCell, "გასვლა") != null) {
            return "baseLeaving";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "შესვენება") != null) {
            return "break";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "დაბრუნება") != null) {
            return "baseReturn";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "ბრუნი") != null) {
            return "round";
        }
    }

    private function createTripPeriodFromLeftSide($row, $type) {

        $startTimeScheduled = $this->time24($row[16]["value"]);
        $arrivalTimeScheduled = $this->time24($row[19]["value"]);
        $tripPeriod = new TripPeriodGuaranteed($type, $startTimeScheduled, $arrivalTimeScheduled);
        return $tripPeriod;
    }

    private function createTripPeriodFromRightSide($row, $type) {
        $startTimeScheduled = $this->time24($row[22]["value"]);
        $arrivalTimeScheduled = $this->time24($row[25]["value"]);
        $tripPeriod = new TripPeriodGuaranteed($type, $startTimeScheduled, $arrivalTimeScheduled);
        return $tripPeriod;
    }

    private function addPreviosTripPeriodTimes($tripPeriod, $tripPeriods) {

        $previousTripPeriod = $tripPeriods[count($tripPeriods) - 1];
        $previousTripPeriodArrivalTimeActual = $previousTripPeriod->getArrivalTimeActual();
        $previousTripPeriodArrivalTimeScheduled = $previousTripPeriod->getArrivalTimeScheduled();
        $tripPeriod->setPreviousTripPeriodArrivalTimeActual($previousTripPeriodArrivalTimeActual);
        $tripPeriod->setPreviousTripPeriodArrivalTimeScheduled($previousTripPeriodArrivalTimeScheduled);
        return $tripPeriod;
    }

    private function time24($shortTimeStamp) {
        if ($shortTimeStamp == "") {
            return $shortTimeStamp;
        }
        $splittedTime = explode(":", $shortTimeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        $totalSeconds = ($hours * 60 * 60) + ($minutes * 60);
        if ($totalSeconds > 3 * 60 * 60) {
            return $shortTimeStamp;
        } else {
            $totalSeconds += 24 * 60 * 60;
            $h = floor($totalSeconds / 3600);
            $m = floor(($totalSeconds - ($h * 3600)) / 60);

            return sprintf('%02d', $h) . ":" . sprintf('%02d', $m);
        }
    }

//Calculation Part

    private function calculateGuarantyRoutesData($guarantyRoutes, $routesFromDB) {
        foreach ($guarantyRoutes as $route) {
            $routeNumber = $route->getNumber();
            if (array_key_exists($routeNumber, $routesFromDB)) {
                $routeFromDB = $routesFromDB[$routeNumber];
                $route->setAPoint($routeFromDB->getAPoint());
                $route->setBPoint($routeFromDB->getBPoint());
                $route->setScheme($routeFromDB->getScheme());
            }
            $exoduses = $route->getExoduses();
            foreach ($exoduses as $exodus) {
                $tripPeriods = $exodus->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $tripPeriodType = $tripPeriod->getType();
                    if ($tripPeriodType == "A_baseReturn" || $tripPeriodType == "B_baseReturn") {
                        $tripPeriodStartTime = $tripPeriod->getStartTime();
                        $routeEndTime = $route->getRouteEndTime();
                        if ($routeEndTime == "") {
                            $route->setRouteEndTime($tripPeriodStartTime);
                        } else {
                            if ($tripPeriodStartTime > $routeEndTime) {
                                $route->setRouteEndTime($tripPeriodStartTime);
                            }
                        }
                    } else {
                        $tripPeriodStartTime = $tripPeriod->getStartTime();
                        if ($tripPeriodType == "ab") {
                            $timeTable = $route->getABTimeTable();
                            array_push($timeTable, $tripPeriodStartTime);
                            $route->setABTimeTable($timeTable);
                        }
                        if ($tripPeriodType == "ba") {
                            $timeTable = $route->getBATimeTable();
                            array_push($timeTable, $tripPeriodStartTime);
                            $route->setBATimeTable($timeTable);
                        }
                    }
                }
            }
            $ABTimeTable = $route->getABTimeTable();
            sort($ABTimeTable);
            $route->setABTimeTable($ABTimeTable);
            $BATimeTable = $route->getBATimeTable();
            sort($BATimeTable);
            $route->setBATimeTable($BATimeTable);
        }
        return $guarantyRoutes;
    }

//////////////export part


    public function exportGuarantyRoutes($guarantyRoutes) {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()->setCellValue('A1', "რიგითი №");
        $spreadsheet->getActiveSheet()->setCellValue('B1', "ავტო ბაზა");
        $spreadsheet->getActiveSheet()->setCellValue('C1', "მარშრუტის №");
        $spreadsheet->getActiveSheet()->setCellValue('D1', "ავტობუსების მარშრუტების მოძრაობის სქემა");
        $spreadsheet->getActiveSheet()->setCellValue('E1', "მარშრუტის ბრუნის სიგრძე, კმ");
        $spreadsheet->getActiveSheet()->setCellValue('F1', "ავტობუსის ტიპი");
        $spreadsheet->getActiveSheet()->setCellValue('G1', "FORMULA HERE '=SUBTOTAL(9;H5:H138)'");
        $spreadsheet->getActiveSheet()->setCellValue('H1', "ინტერვალი, წთ");
        $spreadsheet->getActiveSheet()->setCellValue('I1', "ბრუნის დრო");
        $spreadsheet->getActiveSheet()->setCellValue('J1', "ბრუნების ჯამური რაოდენობა");
        $spreadsheet->getActiveSheet()->setCellValue('K1', "დაწყების დრო");
        $spreadsheet->getActiveSheet()->setCellValue('L1', "დასრულების დრო");
        $spreadsheet->getActiveSheet()->setCellValue('M1', "ხაზზე დასრულების დრო");
        $spreadsheet->getActiveSheet()->setCellValue('N1', "საგარანტიო გასვლები");
        $spreadsheet->getActiveSheet()->setCellValue('T1', "შენიშვნა");
//SECOND ROW
        $spreadsheet->getActiveSheet()->setCellValue('H2', "მომუშავე ავტობუსების რაოდენობა");
        $spreadsheet->getActiveSheet()->setCellValue('G2', "მომუშავე ავტობუსების რაოდენობა");

//THIRD ROW 
        $spreadsheet->getActiveSheet()->setCellValue('N3', "პუნქტი \"A\"");
        $spreadsheet->getActiveSheet()->setCellValue('O3', "გასვლები \"A\" პუნქტიდან");
        $spreadsheet->getActiveSheet()->setCellValue('Q3', "პუნქტი \"B\"");
        $spreadsheet->getActiveSheet()->setCellValue('R3', "გასვლები \"B\" პუნქტიდან");
//FOURTH ROW
        $spreadsheet->getActiveSheet()->setCellValue('A4', "1");
        $spreadsheet->getActiveSheet()->setCellValue('B4', "2");
        $spreadsheet->getActiveSheet()->setCellValue('C4', "3");
        $spreadsheet->getActiveSheet()->setCellValue('D4', "4");
        $spreadsheet->getActiveSheet()->setCellValue('E4', "5");
        $spreadsheet->getActiveSheet()->setCellValue('F4', "6");
        $spreadsheet->getActiveSheet()->setCellValue('G4', "7");
        $spreadsheet->getActiveSheet()->setCellValue('H4', "8");
        $spreadsheet->getActiveSheet()->setCellValue('I4', "9");
        $spreadsheet->getActiveSheet()->setCellValue('J4', "10");
        $spreadsheet->getActiveSheet()->setCellValue('K4', "12");
        $spreadsheet->getActiveSheet()->setCellValue('L4', "13");
        $spreadsheet->getActiveSheet()->setCellValue('M4', "14");
        $spreadsheet->getActiveSheet()->setCellValue('N4', "15");
        $spreadsheet->getActiveSheet()->setCellValue('O4', "16");
        $spreadsheet->getActiveSheet()->setCellValue('P4', "17");
        $spreadsheet->getActiveSheet()->setCellValue('Q4', "18");
        $spreadsheet->getActiveSheet()->setCellValue('R4', "19");
        $spreadsheet->getActiveSheet()->setCellValue('S4', "20");
        $spreadsheet->getActiveSheet()->setCellValue('T4', "21");
//merging cells in header
        $spreadsheet->getActiveSheet()->mergeCells("A1:A3");
        $spreadsheet->getActiveSheet()->mergeCells("B1:B3");
        $spreadsheet->getActiveSheet()->mergeCells("C1:C3");
        $spreadsheet->getActiveSheet()->mergeCells("D1:D3");
        $spreadsheet->getActiveSheet()->mergeCells("E1:E3");
        $spreadsheet->getActiveSheet()->mergeCells("F1:F3");
        $spreadsheet->getActiveSheet()->mergeCells("G2:G3");
        $spreadsheet->getActiveSheet()->mergeCells("H1:H3");
        $spreadsheet->getActiveSheet()->mergeCells("I1:I3");
        $spreadsheet->getActiveSheet()->mergeCells("J1:J3");
        $spreadsheet->getActiveSheet()->mergeCells("K1:K3");
        $spreadsheet->getActiveSheet()->mergeCells("L1:L3");
        $spreadsheet->getActiveSheet()->mergeCells("M1:M3");
        $spreadsheet->getActiveSheet()->mergeCells("N1:S2");
        $spreadsheet->getActiveSheet()->mergeCells("O3:P3");
        $spreadsheet->getActiveSheet()->mergeCells("R3:S3");
        $spreadsheet->getActiveSheet()->mergeCells("T1:T3");
//$spreadsheet->getActiveSheet()->mergeCells("E3:S3");
//TEXT ROTATION 
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('E1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('F1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('G1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('G2')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('H1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('I1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('J1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('K1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('L1')->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('M1')->getAlignment()->setTextRotation(90);

// HEADER ROWS HEIGH
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(50);
//columns widths
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(27);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(15);
//wrapping header text
        $spreadsheet->getActiveSheet()->getStyle('A1:T3')
                ->getAlignment()->setWrapText(true);
//text alignment 
        $spreadsheet->getActiveSheet()->getStyle('A1:T3')
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:T3')
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

//HEADER COLOR
        $spreadsheet->getActiveSheet()->getStyle('A1:T3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('CDC6B4');
        $spreadsheet->getActiveSheet()->getStyle('A4:T4')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('2AA146');
//border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:T3')->applyFromArray($styleArray);


//here goes rows
        $aa = 1;
        $row = 5;
        foreach ($guarantyRoutes as $route) {
            $routeNumber = $route->getNumber();
            $baseNumber = $route->getBaseNumber();
            $busType = $route->getBusType();
            $exoduseNumber = $route->getExodusesNumber();
            $routeStartTime = $route->getRouteStartTime();
            $routeEndTime = $route->getRouteEndTime();
            $abGuarantyTripPeriodStartTime = $route->getABGuarantyTripPeriodStartTime();
            $abSubGuarantyTripPeriodStartTime = $route->getABSubGuarantyTripPeriodStartTime();
            $baGuarantyTripPeriodStartTime = $route->getBAGuarantyTripPeriodStartTime();
            $baSubGuarantyTripPeriodStartTime = $route->getBASubGuarantyTripPeriodStartTime();
            $standartIntervalTime = $route->getStandartIntervalTime();
            $standartTripPeriodTime = $route->getStandartTripPeriodTime();
            $aPoint = $route->getAPoint();
            $bPoint = $route->getBPoint();
            $routeScheme = $route->getScheme();
            $spreadsheet->getActiveSheet()->setCellValue("A$row", $aa);
            $spreadsheet->getActiveSheet()->setCellValue("B$row", $baseNumber);
            $spreadsheet->getActiveSheet()->setCellValue("C$row", $routeNumber);
            $spreadsheet->getActiveSheet()->setCellValue("F$row", $busType);
            $spreadsheet->getActiveSheet()->setCellValue("G$row", $exoduseNumber);
            $spreadsheet->getActiveSheet()->setCellValue("K$row", $routeStartTime);
            $spreadsheet->getActiveSheet()->setCellValue("M$row", $routeEndTime);
            $spreadsheet->getActiveSheet()->setCellValue("P$row", $abGuarantyTripPeriodStartTime);
            $spreadsheet->getActiveSheet()->setCellValue("O$row", $abSubGuarantyTripPeriodStartTime);
            $spreadsheet->getActiveSheet()->setCellValue("S$row", $baGuarantyTripPeriodStartTime);
            $spreadsheet->getActiveSheet()->setCellValue("R$row", $baSubGuarantyTripPeriodStartTime);
            $spreadsheet->getActiveSheet()->setCellValue("H$row", $standartIntervalTime);
            $spreadsheet->getActiveSheet()->setCellValue("I$row", $standartTripPeriodTime);
            $spreadsheet->getActiveSheet()->setCellValue("D$row", $routeScheme);
            $spreadsheet->getActiveSheet()->setCellValue("N$row", $aPoint);
            $spreadsheet->getActiveSheet()->setCellValue("Q$row", $bPoint);

            $aa++;
            $row++;
        }


        /*
        $e = microtime(true);
        echo "Time:" . ($e - $this->s);
        echo "<br>";
        echo 'Peak usage:(' . ( (memory_get_peak_usage() / 1024 ) / 1024) . 'M) <br>';
         */
         $this->exportFile($spreadsheet);
    }

    //---------------//----------------------//-------------------------//-----------------
    private function exportFile($spreadsheet) {
        $filename = 'tmps/' . time() . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        header('Content-Type: application/x-www-form-urlencoded');

        header('Content-Transfer-Encoding: Binary');

        header("Content-disposition: attachment; filename=\"" . $filename . "\"");

        readfile($filename);

        unlink($filename);
    }

}
