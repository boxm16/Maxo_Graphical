<?php

require 'vendor/autoload.php';
require_once 'TimeCalculator.php';
require_once 'Model/TripPeriodDataCarrier.php';
require_once 'DAO/DataBaseTools.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportController {

    private $timeCalculator;
    private $context;

    function __construct() {
        $this->timeCalculator = new TimeCalculator();

        $this->context = "http://berishvili.eu5.org";
        $server = $_SERVER['SERVER_NAME'];
        if ($server == "localhost") {
            $this->context = "http://localhost/Maxo_Graphical";
        }
    }

    public function exportGuaranteedTripPeriods($routes) {

        $dataBaseTools = new DataBaseTools();
        $routePoints = $dataBaseTools->getRoutePoints(); //associative array with key=routeNumber and value is a route with names

        $spreadsheet = new Spreadsheet();


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(10);

        $spreadsheet->getActiveSheet()->getStyle("E1:S2")->getFont()->setSize(14);



        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D2:I2')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('L2:Q2')->getAlignment()->setWrapText(true);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C1', 'საგარანტიო რეისები');
        $sheet->setCellValue('E1', 'A_B');
        $sheet->setCellValue('M1', 'B_A');
        $sheet->setCellValue('A2', 'მარშ. #');
        $sheet->getStyle('A2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('B2', 'A პუნკტი');
        $sheet->setCellValue('C2', 'B პუნკტი');
        $sheet->setCellValue('D2', 'თარიღი');
        $sheet->setCellValue('E2', 'მძღოლი');
        $sheet->setCellValue('F2', 'გეგმიური გასვლის დრო');
        $sheet->getStyle('F2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('G2', 'ფაქტიური გასვლის დრო ');
        $sheet->getStyle('G2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('H2', 'გასვლის ნომერი');
        $sheet->getStyle('H2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('I2', 'GPS გასვლის ნომერი');
        $sheet->getStyle('I2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('J2', 'GPS გეგმიური გასვლის დრო');
        $sheet->getStyle('J2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('K2', 'ფაქტიური გასვლის დრო');
        $sheet->getStyle('K2')->getAlignment()->setTextRotation(90);
        //  $sheet->setCellValue('J2', '=SUBTOTAL(9,J3:J114)'); THIS IS WRITTEN AT THE END OF THIS FUNCTION, TO SEE WHAT ROW IS LAST

        $sheet->setCellValue('M2', 'მძღოლი');
        $sheet->setCellValue('N2', 'გეგმიური გასვლის დრო');
        $sheet->getStyle('N2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('O2', 'ფაქტიური გასვლის დრო ');
        $sheet->getStyle('O2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('P2', 'გასვლის ნომერი');
        $sheet->getStyle('P2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('Q2', 'GPS გასვლის ნომერი');
        $sheet->getStyle('Q2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('R2', 'GPS გეგმიური გასვლის დრო');
        $sheet->getStyle('R2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('S2', 'ფაქტიური გასვლის დრო');
        $sheet->getStyle('S2')->getAlignment()->setTextRotation(90);

        $sheet->getStyle('A:S')->getAlignment()->setHorizontal('center'); //this align all cell texts to center
        $sheet->mergeCells("E1:K1");
        $sheet->mergeCells("M1:S1");
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],
            ],
        ];


        $row = 3;
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();

            $aPoint = $routePoints[$routeNumber]->getAPoint();
            $bPoint = $routePoints[$routeNumber]->getBPoint();


            $days = $route->getDays();
            foreach ($days as $day) {
                $dateStamp = $day->getDateStamp();
                $lastTrips = $day->getLastTrips();
                $ab_lastTripPeriodScheduled = $lastTrips["ab_lastTripPeriodScheduled"];
                $ba_lastTripPeriodScheduled = $lastTrips["ba_lastTripPeriodScheduled"];
                $ab_lastTripPeriodActual = $lastTrips["ab_lastTripPeriodActual"];
                $ba_lastTripPeriodActual = $lastTrips["ba_lastTripPeriodActual"];

                $ab_light = "white";
                $ba_light = "white";
                if ($ab_lastTripPeriodScheduled != null) {
                    $ab_lastTripPeriodStartTimeScheduled = $ab_lastTripPeriodScheduled->getStartTimeScheduled();
                    $ab_lastTripPeriodStartTimeActual = $ab_lastTripPeriodScheduled->getStartTimeActual();
                    $ab_lastTripPeriodExodusNumber = $ab_lastTripPeriodScheduled->getTripPeriodDNA()->getExodusNumber();
                    $ab_lastTripPeriodType = $ab_lastTripPeriodScheduled->getType();
                    $ab_driverName = $ab_lastTripPeriodScheduled->getTripPeriodDNA()->getDriverName();
                } else {
                    $ab_lastTripPeriodStartTimeScheduled = "რ.ვ.ი.*";
                    $ab_lastTripPeriodStartTimeActual = "რ.ვ.ი.*";
                    $ab_lastTripPeriodExodusNumber = "რ.ვ.ი.*";
                    $ab_lastTripPeriodType = "რ.ვ.ი.*";
                }
                if ($ba_lastTripPeriodScheduled != null) {
                    $ba_lastTripPeriodStartTimeScheduled = $ba_lastTripPeriodScheduled->getStartTimeScheduled();
                    $ba_lastTripPeriodStartTimeActual = $ba_lastTripPeriodScheduled->getStartTimeActual();
                    $ba_lastTripPeriodExodusNumber = $ba_lastTripPeriodScheduled->getTripPeriodDNA()->getExodusNumber();
                    $ba_lastTripPeriodType = $ba_lastTripPeriodScheduled->getType();
                    $ba_driverName = $ba_lastTripPeriodScheduled->getTripPeriodDNA()->getDriverName();
                } else {
                    $ba_lastTripPeriodStartTimeScheduled = "რ.ვ.ი.*";
                    $ba_lastTripPeriodStartTimeActual = "რ.ვ.ი.*";
                    $ba_lastTripPeriodExodusNumber = "რ.ვ.ი.*";
                    $ba_lastTripPeriodType = "რ.ვ.ი.*";
                }
                if ($ab_lastTripPeriodActual != null) {
                    $gps_ab_lastTripScheduled = $ab_lastTripPeriodActual->getStartTimeScheduled();
                    $gps_ab_lastTripActual = $ab_lastTripPeriodActual->getStartTimeActual();
                    $gps_ab_lastTripExodusNumber = $ab_lastTripPeriodActual->getTripPeriodDNA()->getExodusNumber();
                    $gps_ab_lastTripPeriodType = $ab_lastTripPeriodActual->getType();
                } else {
                    $gps_ab_lastTripScheduled = "რ.ვ.ი.*";
                    $gps_ab_lastTripActual = "რ.ვ.ი.*";
                    $gps_ab_lastTripExodusNumber = "რ.ვ.ი.*";
                    $gps_ab_lastTripPeriodType = "რ.ვ.ი.*";
                }

                if ($ba_lastTripPeriodActual != null) {
                    $gps_ba_lastTripScheduled = $ba_lastTripPeriodActual->getStartTimeScheduled();
                    $gps_ba_lastTripActual = $ba_lastTripPeriodActual->getStartTimeActual();
                    $gps_ba_lastTripExodusNumber = $ba_lastTripPeriodActual->getTripPeriodDNA()->getExodusNumber();
                    $gps_ba_lastTripPeriodType = $ba_lastTripPeriodActual->getType();
                } else {
                    $gps_ba_lastTripScheduled = "რ.ვ.ი.*";
                    $gps_ba_lastTripActual = "რ.ვ.ი.*";
                    $gps_ba_lastTripExodusNumber = "რ.ვ.ი.*";
                    $gps_ba_lastTripPeriodType = "რ.ვ.ი.*";
                }
//HERE START CHECKING ALGORITHM
                if ($ab_lastTripPeriodScheduled != null && $ab_lastTripPeriodActual != null) {
                    if ($ab_lastTripPeriodStartTimeActual != "") {
                        $ab_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeScheduled);
                        $ab_lastTripPeriodStartTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeActual);
                        if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $ab_lastTripPeriodStartTimeActualInSeconds) > 60) {
                            $ab_light = "yellow";
                            if ($gps_ab_lastTripActual != "") {
                                $gps_ab_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ab_lastTripActual);
                                if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $gps_ab_lastTripActualInSeconds) > 60) {
                                    $ab_light = "red";
                                }
                            }
                        }
                    } else {
                        $ab_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeScheduled);
                        $gps_ab_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ab_lastTripActual);

                        if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $gps_ab_lastTripActualInSeconds) > 60) {
                            $ab_light = "red";
                        }
                    }
                }

                if ($ba_lastTripPeriodScheduled != null && $ba_lastTripPeriodActual != null) {
                    if ($ba_lastTripPeriodStartTimeActual != "") {
                        $ba_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeScheduled);
                        $ba_lastTripPeriodStartTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeActual);
                        if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $ba_lastTripPeriodStartTimeActualInSeconds) > 60) {
                            $ba_light = "yellow";
                            if ($gps_ba_lastTripActual != "") {
                                $gps_ba_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ba_lastTripActual);
                                if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $gps_ba_lastTripActualInSeconds) > 60) {
                                    $ba_light = "red";
                                }
                            }
                        }
                    } else {
                        $ba_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeScheduled);
                        $gps_ba_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ba_lastTripActual);

                        if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $gps_ba_lastTripActualInSeconds) > 60) {
                            $ba_light = "red";
                        }
                    }
                }
                $middle_light = "white";
                $sheet->setCellValue("L$row", "0");
                if (($ab_light == "yellow" || $ba_light == "yellow")) {
                    $middle_light = "yellow";
                }
                if ($ab_light == "red" || $ba_light == "red") {
                    $middle_light = "red";
                    $sheet->setCellValue("L$row", "1");
                }
                if ($ab_light == "red" && $ba_light == "red") {
                    $middle_light = $ab_light;
                    $sheet->setCellValue("L$row", "2");
                }


                $ab_light = $this->convertColor($ab_light);
                $ba_light = $this->convertColor($ba_light);
                $middle_light = $this->convertColor($middle_light);
                $sheet->setCellValue("A$row", $routeNumber);
                $sheet->setCellValue("B$row", $aPoint);
                $sheet->setCellValue("C$row", $bPoint);
                $sheet->setCellValue("D$row", $dateStamp);
                $sheet->setCellValue("E$row", $ab_driverName);
                $sheet->setCellValue("F$row", $ab_lastTripPeriodStartTimeScheduled);
                $sheet->setCellValue("G$row", $ab_lastTripPeriodStartTimeActual);

                $sheet->getStyle("G$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($ab_light);

                $sheet->setCellValue("H$row", $ab_lastTripPeriodExodusNumber);
                $tripPeriodType = "ab";
                $sheet->getCell("H$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$ab_lastTripPeriodStartTimeScheduled");

                $sheet->setCellValue("I$row", $gps_ab_lastTripExodusNumber);
                $sheet->getCell("I$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$gps_ab_lastTripExodusNumber");

                $sheet->setCellValue("J$row", $gps_ab_lastTripScheduled);
                $sheet->setCellValue("K$row", $gps_ab_lastTripActual);


                $sheet->getStyle("L$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($middle_light);


                $sheet->setCellValue("M$row", $ba_driverName);
                $sheet->setCellValue("N$row", $ba_lastTripPeriodStartTimeScheduled);
                $sheet->setCellValue("O$row", $ba_lastTripPeriodStartTimeActual);


                $spreadsheet->getActiveSheet()->getStyle("O$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($ba_light);

                $sheet->setCellValue("P$row", $ba_lastTripPeriodExodusNumber);
                $tripPeriodType = "ba";
                $sheet->getCell("P$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$ba_lastTripPeriodStartTimeScheduled");

                $sheet->setCellValue("Q$row", $gps_ba_lastTripExodusNumber);
                $sheet->getCell("Q$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$gps_ba_lastTripExodusNumber");

                $sheet->setCellValue("R$row", $gps_ba_lastTripScheduled);
                $sheet->setCellValue("S$row", $gps_ba_lastTripActual);


                $row++;
            }
        }

        $sheet->setCellValue('L2', "=SUBTOTAL(9,L3:L$row)");

        $this->exportFile($spreadsheet);
    }

    /////////////this is maybe for deleteion



    public function exportGuaranteedTripPeriodsNewVersion($routes) {

        $dataBaseTools = new DataBaseTools();
        $routePoints = $dataBaseTools->getRoutePoints(); //associative array with key=routeNumber and value is a route with names

        $spreadsheet = new Spreadsheet();


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(10);

        $spreadsheet->getActiveSheet()->getStyle("E1:S2")->getFont()->setSize(14);



        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D2:I2')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('L2:Q2')->getAlignment()->setWrapText(true);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C1', 'საგარანტიო რეისები მისვლების გათვალისწინებით');
        $sheet->setCellValue('E1', 'A_B');
        $sheet->setCellValue('M1', 'B_A');
        $sheet->setCellValue('A2', 'მარშ. #');
        $sheet->getStyle('A2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('B2', 'A პუნკტი');
        $sheet->setCellValue('C2', 'B პუნკტი');
        $sheet->setCellValue('D2', 'თარიღი');
        $sheet->setCellValue('E2', 'მძღოლი');
        $sheet->setCellValue('F2', 'გეგმიური გასვლის დრო');
        $sheet->getStyle('F2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('G2', 'ფაქტიური გასვლის დრო ');
        $sheet->getStyle('G2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('H2', 'გასვლის ნომერი');
        $sheet->getStyle('H2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('I2', 'GPS გასვლის ნომერი');
        $sheet->getStyle('I2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('J2', 'GPS გეგმიური გასვლის დრო');
        $sheet->getStyle('J2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('K2', 'ფაქტიური გასვლის დრო');
        $sheet->getStyle('K2')->getAlignment()->setTextRotation(90);
        //  $sheet->setCellValue('J2', '=SUBTOTAL(9,J3:J114)'); THIS IS WRITTEN AT THE END OF THIS FUNCTION, TO SEE WHAT ROW IS LAST

        $sheet->setCellValue('M2', 'მძღოლი');
        $sheet->setCellValue('N2', 'გეგმიური გასვლის დრო');
        $sheet->getStyle('N2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('O2', 'ფაქტიური გასვლის დრო ');
        $sheet->getStyle('O2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('P2', 'გასვლის ნომერი');
        $sheet->getStyle('P2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('Q2', 'GPS გასვლის ნომერი');
        $sheet->getStyle('Q2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('R2', 'GPS გეგმიური გასვლის დრო');
        $sheet->getStyle('R2')->getAlignment()->setTextRotation(90);
        $sheet->setCellValue('S2', 'ფაქტიური გასვლის დრო');
        $sheet->getStyle('S2')->getAlignment()->setTextRotation(90);

        $sheet->getStyle('A:S')->getAlignment()->setHorizontal('center'); //this align all cell texts to center
        $sheet->mergeCells("E1:K1");
        $sheet->mergeCells("M1:S1");
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],
            ],
        ];


        $row = 3;
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();

            $aPoint = $routePoints[$routeNumber]->getAPoint();
            $bPoint = $routePoints[$routeNumber]->getBPoint();


            $days = $route->getDays();
            foreach ($days as $day) {
                $dateStamp = $day->getDateStamp();
                $lastTrips = $day->getLastTripsNew();
                $ab_lastTripPeriodScheduled = $lastTrips["ab_lastTripPeriodScheduled"];
                $ba_lastTripPeriodScheduled = $lastTrips["ba_lastTripPeriodScheduled"];
                $ab_lastTripPeriodActual = $lastTrips["ab_lastTripPeriodActual"];
                $ba_lastTripPeriodActual = $lastTrips["ba_lastTripPeriodActual"];

                $ab_light = "white";
                $ba_light = "white";
                if ($ab_lastTripPeriodScheduled != null) {
                    $ab_lastTripPeriodStartTimeScheduled = $ab_lastTripPeriodScheduled->getStartTimeScheduled();
                    $ab_lastTripPeriodStartTimeActual = $ab_lastTripPeriodScheduled->getStartTimeActual();
                    $ab_lastTripPeriodExodusNumber = $ab_lastTripPeriodScheduled->getTripPeriodDNA()->getExodusNumber();
                    $ab_lastTripPeriodType = $ab_lastTripPeriodScheduled->getType();
                    $ab_driverName = $ab_lastTripPeriodScheduled->getTripPeriodDNA()->getDriverName();
                } else {
                    $ab_lastTripPeriodStartTimeScheduled = "რ.ვ.ი.*";
                    $ab_lastTripPeriodStartTimeActual = "რ.ვ.ი.*";
                    $ab_lastTripPeriodExodusNumber = "რ.ვ.ი.*";
                    $ab_lastTripPeriodType = "რ.ვ.ი.*";
                }
                if ($ba_lastTripPeriodScheduled != null) {
                    $ba_lastTripPeriodStartTimeScheduled = $ba_lastTripPeriodScheduled->getStartTimeScheduled();
                    $ba_lastTripPeriodStartTimeActual = $ba_lastTripPeriodScheduled->getStartTimeActual();
                    $ba_lastTripPeriodExodusNumber = $ba_lastTripPeriodScheduled->getTripPeriodDNA()->getExodusNumber();
                    $ba_lastTripPeriodType = $ba_lastTripPeriodScheduled->getType();
                    $ba_driverName = $ba_lastTripPeriodScheduled->getTripPeriodDNA()->getDriverName();
                } else {
                    $ba_lastTripPeriodStartTimeScheduled = "რ.ვ.ი.*";
                    $ba_lastTripPeriodStartTimeActual = "რ.ვ.ი.*";
                    $ba_lastTripPeriodExodusNumber = "რ.ვ.ი.*";
                    $ba_lastTripPeriodType = "რ.ვ.ი.*";
                }
                if ($ab_lastTripPeriodActual != null) {
                    $gps_ab_lastTripScheduled = $ab_lastTripPeriodActual->getStartTimeScheduled();
                    $gps_ab_lastTripActual = $ab_lastTripPeriodActual->getStartTimeActual();
                    $gps_ab_lastTripExodusNumber = $ab_lastTripPeriodActual->getTripPeriodDNA()->getExodusNumber();
                    $gps_ab_lastTripPeriodType = $ab_lastTripPeriodActual->getType();
                } else {
                    $gps_ab_lastTripScheduled = "რ.ვ.ი.*";
                    $gps_ab_lastTripActual = "რ.ვ.ი.*";
                    $gps_ab_lastTripExodusNumber = "რ.ვ.ი.*";
                    $gps_ab_lastTripPeriodType = "რ.ვ.ი.*";
                }

                if ($ba_lastTripPeriodActual != null) {
                    $gps_ba_lastTripScheduled = $ba_lastTripPeriodActual->getStartTimeScheduled();
                    $gps_ba_lastTripActual = $ba_lastTripPeriodActual->getStartTimeActual();
                    $gps_ba_lastTripExodusNumber = $ba_lastTripPeriodActual->getTripPeriodDNA()->getExodusNumber();
                    $gps_ba_lastTripPeriodType = $ba_lastTripPeriodActual->getType();
                } else {
                    $gps_ba_lastTripScheduled = "რ.ვ.ი.*";
                    $gps_ba_lastTripActual = "რ.ვ.ი.*";
                    $gps_ba_lastTripExodusNumber = "რ.ვ.ი.*";
                    $gps_ba_lastTripPeriodType = "რ.ვ.ი.*";
                }
//HERE START CHECKING ALGORITHM
                if ($ab_lastTripPeriodScheduled != null && $ab_lastTripPeriodActual != null) {
                    if ($ab_lastTripPeriodStartTimeActual != "") {
                        $ab_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeScheduled);
                        $ab_lastTripPeriodStartTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeActual);
                        if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $ab_lastTripPeriodStartTimeActualInSeconds) > 60) {
                            $ab_light = "yellow";
                            if ($gps_ab_lastTripActual != "") {
                                $gps_ab_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ab_lastTripActual);
                                if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $gps_ab_lastTripActualInSeconds) > 60) {
                                    $ab_light = "red";
                                }
                            }
                        }
                    } else {
                        $ab_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ab_lastTripPeriodStartTimeScheduled);
                        $gps_ab_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ab_lastTripActual);

                        if (($ab_lastTripPeriodStartTimeScheduledInSeconds - $gps_ab_lastTripActualInSeconds) > 60) {
                            $ab_light = "red";
                        }
                    }
                }

                if ($ba_lastTripPeriodScheduled != null && $ba_lastTripPeriodActual != null) {
                    if ($ba_lastTripPeriodStartTimeActual != "") {
                        $ba_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeScheduled);
                        $ba_lastTripPeriodStartTimeActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeActual);
                        if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $ba_lastTripPeriodStartTimeActualInSeconds) > 60) {
                            $ba_light = "yellow";
                            if ($gps_ba_lastTripActual != "") {
                                $gps_ba_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ba_lastTripActual);
                                if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $gps_ba_lastTripActualInSeconds) > 60) {
                                    $ba_light = "red";
                                }
                            }
                        }
                    } else {
                        $ba_lastTripPeriodStartTimeScheduledInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($ba_lastTripPeriodStartTimeScheduled);
                        $gps_ba_lastTripActualInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($gps_ba_lastTripActual);

                        if (($ba_lastTripPeriodStartTimeScheduledInSeconds - $gps_ba_lastTripActualInSeconds) > 60) {
                            $ba_light = "red";
                        }
                    }
                }
                $middle_light = "white";
                $sheet->setCellValue("L$row", "0");
                if (($ab_light == "yellow" || $ba_light == "yellow")) {
                    $middle_light = "yellow";
                }
                if ($ab_light == "red" || $ba_light == "red") {
                    $middle_light = "red";
                    $sheet->setCellValue("L$row", "1");
                }
                if ($ab_light == "red" && $ba_light == "red") {
                    $middle_light = $ab_light;
                    $sheet->setCellValue("L$row", "2");
                }


                $ab_light = $this->convertColor($ab_light);
                $ba_light = $this->convertColor($ba_light);
                $middle_light = $this->convertColor($middle_light);
                $sheet->setCellValue("A$row", $routeNumber);
                $sheet->setCellValue("B$row", $aPoint);
                $sheet->setCellValue("C$row", $bPoint);
                $sheet->setCellValue("D$row", $dateStamp);
                $sheet->setCellValue("E$row", $ab_driverName);
                $sheet->setCellValue("F$row", $ab_lastTripPeriodStartTimeScheduled);
                $sheet->setCellValue("G$row", $ab_lastTripPeriodStartTimeActual);

                $sheet->getStyle("G$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($ab_light);

                $sheet->setCellValue("H$row", $ab_lastTripPeriodExodusNumber);
                $tripPeriodType = "ab";
                $sheet->getCell("H$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$ab_lastTripPeriodStartTimeScheduled");

                $sheet->setCellValue("I$row", $gps_ab_lastTripExodusNumber);
                $sheet->getCell("I$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$gps_ab_lastTripExodusNumber");

                $sheet->setCellValue("J$row", $gps_ab_lastTripScheduled);
                $sheet->setCellValue("K$row", $gps_ab_lastTripActual);


                $sheet->getStyle("L$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($middle_light);


                $sheet->setCellValue("M$row", $ba_driverName);
                $sheet->setCellValue("N$row", $ba_lastTripPeriodStartTimeScheduled);
                $sheet->setCellValue("O$row", $ba_lastTripPeriodStartTimeActual);


                $spreadsheet->getActiveSheet()->getStyle("O$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($ba_light);

                $sheet->setCellValue("P$row", $ba_lastTripPeriodExodusNumber);
                $tripPeriodType = "ba";
                $sheet->getCell("P$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$ba_lastTripPeriodStartTimeScheduled");

                $sheet->setCellValue("Q$row", $gps_ba_lastTripExodusNumber);
                $sheet->getCell("Q$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$gps_ba_lastTripExodusNumber");

                $sheet->setCellValue("R$row", $gps_ba_lastTripScheduled);
                $sheet->setCellValue("S$row", $gps_ba_lastTripActual);


                $row++;
            }
        }

        $sheet->setCellValue('L2', "=SUBTOTAL(9,L3:L$row)");

        $this->exportFile($spreadsheet);
    }

    //////////////////////////////// this is end of part of maybe deletion

    public function exportExcelForm($routes, $requestedData) {


        $percents = $requestedData["percents"];
        $requestedRouteNumbers = $this->convertDataToArray($requestedData["routeNumber"]);
        $requestedDateStamps = $this->convertDataToArray($requestedData["dateStamp"]);
        $requestedExodusNumbers = $this->convertDataToArray($requestedData["exodusNumber"]);
        $requestedBusNumbers = $this->convertDataToArray($requestedData["busNumber"]);
        $requestedDriverNames = $this->convertDataToArray($requestedData["driverName"]);
        $requestedTripPeriodTypes = $this->convertDataToArray($requestedData["tripPeriodType"]);
        $requestedStartTimesScheduled = $this->convertDataToArray($requestedData["startTimeScheduled"]);
        $requestedStartTimesActual = $this->convertDataToArray($requestedData["startTimeActual"]);
        $requestedArrivalTimesScheduled = $this->convertDataToArray($requestedData["arrivalTimeScheduled"]);
        $requestedArrivalTimesActual = $this->convertDataToArray($requestedData["arrivalTimeActual"]);
        $requesteTripPeriodsScheduled = $this->convertDataToArray($requestedData["tripPeriodScheduled"]);
        $requestedTripPeriodsActual = $this->convertDataToArray($requestedData["tripPeriodActual"]);
        $requestedTripPeriodsDifference = $this->convertDataToArray($requestedData["tripPeriodDifference"]);
//-------------------------------------------------------------------------
        $spreadsheet = new Spreadsheet();


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(15);

        $spreadsheet->getActiveSheet()->getStyle("A1:N1")->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffc0cb');

        $spreadsheet->getActiveSheet()->getStyle('G1')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('F1')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('H1')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('I1')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('J1')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('K1')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('L1')->getAlignment()->setWrapText(true);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'მარშრუტის #');
        $sheet->setCellValue('B1', 'თარიღი');
        $sheet->setCellValue('C1', 'ავტობუსის #');
        $sheet->setCellValue('D1', 'გასვლის #');
        $sheet->setCellValue('E1', 'მძღოლი ');
        $sheet->setCellValue('F1', 'მიმართულება');
        $sheet->setCellValue('G1', 'გასვლის გეგმიური დრო');
        $sheet->setCellValue('H1', 'გასვლის ფაქტიური დრო');
        $sheet->setCellValue('I1', 'მისვლის გეგმიური დრო');
        $sheet->setCellValue('J1', 'მისვლის ფაქტიური დრო');
        $sheet->setCellValue('K1', 'წირის გეგმიური დრო');
        $sheet->setCellValue('L1', 'წირის ფაქტიური დრო');
        $sheet->setCellValue('M1', 'სხვაობა');
        $sheet->setCellValue('N1', 'დეტალურად');

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],
            ],
        ];


        $row = 2;
        $calculationMap = array();
        foreach ($routes as $route) {
            $routeNumber = $route->getNumber();
            if (in_array($routeNumber, $requestedRouteNumbers)) {
                $days = $route->getDays();
                foreach ($days as $day) {
                    $dateStamp = $day->getDateStamp();
                    if (in_array($dateStamp, $requestedDateStamps)) {
                        $exoduses = $day->getExoduses();
                        foreach ($exoduses as $exodus) {
                            $exodusNumber = $exodus->getNumber();
                            if (in_array($exodusNumber, $requestedExodusNumbers)) {
                                $tripVouchers = $exodus->getTripVouchers();
                                foreach ($tripVouchers as $tripVoucher) {
                                    $busNumber = $tripVoucher->getBusNumber();
                                    $driverName = $tripVoucher->getDriverName();
                                    if (in_array($busNumber, $requestedBusNumbers) && in_array($driverName, $requestedDriverNames)) {
                                        $tripPeriods = $tripVoucher->getTripPeriods();
                                        foreach ($tripPeriods as $tripPeriod) {
                                            $tripPeriodType = $tripPeriod->getTypeGe();
                                            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                                            $startTimeActual = $tripPeriod->getStartTimeActual();
                                            $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                                            $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                                            $tripPeriodScheduledTime = $tripPeriod->getTripPeriodScheduledTime();
                                            $tripPeriodActualTime = $tripPeriod->getTripPeriodActualTime();
                                            $tripPeriodDifferenceTime = $tripPeriod->getTripPeriodDifferenceTime();

                                            $tripPeriodDifferenceTimeColor = $this->convertColor($tripPeriod->getTripPeriodDifferenceTimeColor());


                                            if (in_array($tripPeriodType, $requestedTripPeriodTypes) &&
                                                    in_array($startTimeScheduled, $requestedStartTimesScheduled) &&
                                                    in_array($startTimeActual, $requestedStartTimesActual) &&
                                                    in_array($arrivalTimeScheduled, $requestedArrivalTimesScheduled) &&
                                                    in_array($arrivalTimeActual, $requestedArrivalTimesActual) &&
                                                    in_array($tripPeriodScheduledTime, $requesteTripPeriodsScheduled) &&
                                                    in_array($tripPeriodActualTime, $requestedTripPeriodsActual) &&
                                                    in_array($tripPeriodDifferenceTime, $requestedTripPeriodsDifference)
                                            ) {

                                                //this line makes time to be seen in excel when i need to calculate average fo selected cells. but its not working for timestamp with "-" sign

                                                \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());


                                                $sheet->setCellValue("A$row", $routeNumber);
                                                $sheet->setCellValue("B$row", $dateStamp);
                                                $sheet->setCellValue("C$row", $busNumber);
                                                $sheet->setCellValue("D$row", $exodusNumber);
                                                $sheet->setCellValue("E$row", $driverName);
                                                $sheet->setCellValue("F$row", $tripPeriodType);
                                                $sheet->setCellValue("G$row", $startTimeScheduled);
                                                $sheet->setCellValue("H$row", $startTimeActual);
                                                $sheet->setCellValue("I$row", $arrivalTimeScheduled);
                                                $sheet->setCellValue("J$row", $arrivalTimeActual);
                                                $sheet->setCellValue("K$row", $tripPeriodScheduledTime);
                                                $sheet->setCellValue("L$row", $tripPeriodActualTime);
                                                $sheet->setCellValue("M$row", $tripPeriodDifferenceTime);

                                                $sheet->getCell("N$row")->getHyperlink()->setUrl("$this->context/exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled");
                                                $sheet->setCellValue("N$row", "დეტალურად");

                                                $spreadsheet->getActiveSheet()->getStyle("M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($tripPeriodDifferenceTimeColor);

                                                //----------------------//-----------
                                                if ($tripPeriodType == "A_B" || $tripPeriodType == "B_A") {
                                                    if ($tripPeriodActualTime != "" &&
                                                            ($this->lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents) || $this->highPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents))) {
                                                        if ($tripPeriodType == "A_B") {

                                                            if ($this->lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents)) {

                                                                if (array_key_exists($routeNumber, $calculationMap)) {
                                                                    $tripPeriodDataCarrier = $calculationMap[$routeNumber];
                                                                } else {
                                                                    $tripPeriodDataCarrier = new TripPeriodDataCarrier();
                                                                    $tripPeriodDataCarrier->setRouteNumber($routeNumber);
                                                                    $calculationMap[$routeNumber] = $tripPeriodDataCarrier;
                                                                }
                                                                $tripPeriodDataCarrier->setAbLowCount($tripPeriodDataCarrier->getAbLowCount() + 1);
                                                                $tripPeriodDataCarrier->setAbLowTotal($tripPeriodDataCarrier->getAbLowTotal() + $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime));
                                                            } else {//now highPercetnace
                                                                if (array_key_exists($routeNumber, $calculationMap)) {
                                                                    $tripPeriodDataCarrier = $calculationMap[$routeNumber];
                                                                } else {
                                                                    $tripPeriodDataCarrier = new TripPeriodDataCarrier();
                                                                    $tripPeriodDataCarrier->setRouteNumber($routeNumber);
                                                                    $calculationMap[$routeNumber] = $tripPeriodDataCarrier;
                                                                }

                                                                $tripPeriodDataCarrier->setAbHighCount($tripPeriodDataCarrier->getAbHighCount() + 1);
                                                                $tripPeriodDataCarrier->setAbHighTotal($tripPeriodDataCarrier->getAbHighTotal() + $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime));
                                                            }


                                                            $abTripPeriodTimeStandart = $tripPeriodDataCarrier->getAbTripPeriodTimeStandart();
                                                            if ($abTripPeriodTimeStandart == "") {
                                                                $tripPeriodDataCarrier->setAbTripPeriodTimeStandart($tripPeriod->getTripPeriodScheduledTime());
                                                            } else {
                                                                if ($abTripPeriodTimeStandart != $tripPeriod->getTripPeriodScheduledTime()) {
                                                                    $tripPeriodDataCarrier->setAbTripPeriodTimeStandart("მრავალი გეგმიური დრო");
                                                                }
                                                            }
                                                        } else {//now B_A
                                                            if ($this->lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents)) {
                                                                if (array_key_exists($routeNumber, $calculationMap)) {
                                                                    $tripPeriodDataCarrier = $calculationMap[$routeNumber];
                                                                } else {
                                                                    $tripPeriodDataCarrier = new TripPeriodDataCarrier();
                                                                    $tripPeriodDataCarrier->setRouteNumber($routeNumber);
                                                                    $calculationMap[$routeNumber] = $tripPeriodDataCarrier;
                                                                }
                                                                $tripPeriodDataCarrier->setBaLowCount($tripPeriodDataCarrier->getBaLowCount() + 1);
                                                                $tripPeriodDataCarrier->setBaLowTotal($tripPeriodDataCarrier->getBaLowTotal() + $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime));
                                                            } else {//now highPercetnace
                                                                if (array_key_exists($routeNumber, $calculationMap)) {
                                                                    $tripPeriodDataCarrier = $calculationMap[$routeNumber];
                                                                } else {
                                                                    $tripPeriodDataCarrier = new TripPeriodDataCarrier();
                                                                    $tripPeriodDataCarrier->setRouteNumber($routeNumber);
                                                                    $calculationMap[$routeNumber] = $tripPeriodDataCarrier;
                                                                }
                                                                $tripPeriodDataCarrier->setBaHighCount($tripPeriodDataCarrier->getBaHighCount() + 1);
                                                                $tripPeriodDataCarrier->setBaHighTotal($tripPeriodDataCarrier->getBaHighTotal() + $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime));
                                                            }
                                                            $baTripPeriodTimeStandart = $tripPeriodDataCarrier->getBaTripPeriodTimeStandart();
                                                            if ($baTripPeriodTimeStandart == "") {
                                                                $tripPeriodDataCarrier->setBaTripPeriodTimeStandart($tripPeriod->getTripPeriodScheduledTime());
                                                            } else {
                                                                if ($baTripPeriodTimeStandart != $tripPeriod->getTripPeriodScheduledTime()) {
                                                                    $tripPeriodDataCarrier->setBaTripPeriodTimeStandart("მრავალი გეგმიური დრო");
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                $row++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('საშუალოები');
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->getColumnDimension('A')->setWidth(13);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(17);
        $sheet->getColumnDimension('J')->setWidth(17);
        $sheet->getColumnDimension('K')->setWidth(19);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getStyle('A1:L1')->getAlignment()->setWrapText(true);

        $sheet->getStyle("A1:L1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d3d3d3');


        $sheet->setCellValue('A1', 'მარშრუტის #');
        $sheet->setCellValue('B1', 'მიმართულება');
        $sheet->setCellValue('C1', "+$percents% ჩათვლილი რეისების რაოდენობა");
        $sheet->setCellValue('D1', "+$percents% ჩათვლილი რეისების საშუალო ფაქტიური დრო");
        $sheet->setCellValue('E1', "−$percents% ჩათვლილი რეისების რაოდენობა");
        $sheet->setCellValue('F1', "−$percents% ჩათვლილი რეისების  საშუალო ფაქტიური დრო");
        $sheet->setCellValue('G1', 'ყველა ჩათვლილი რეისების რაოდენობა');
        $sheet->setCellValue('H1', 'ყველა ჩათვლილი რეისების საშუალო ფაქტიური დრო');
        $sheet->setCellValue('I1', 'რეისის გეგმიური დრო');
        $sheet->setCellValue('J1', 'ბრუნების გეგმიური დრო');
        $sheet->setCellValue('K1', 'ყველა ჩათვლილი რეისების რაოდენობა');
        $sheet->setCellValue('L1', 'ორივე მიმართულების ბრუნების საშუალო ფაქტიური დრო');

        $sheet->getStyle('A')->getAlignment()->setVertical('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $xRow = 2;
        $yRow;


        $requestedDateStampsString = $requestedData["dateStamp"];
        foreach ($calculationMap as $tripPeriodDataCarrier) {
            $routeNumber = $tripPeriodDataCarrier->getRouteNumber();
            $sheet->setCellValue("A$xRow", $routeNumber);
            $yRow = $xRow + 1;
            $sheet->mergeCells("A$xRow:A$yRow");
            $sheet->setCellValue("B$xRow", "A_B");

            $sheet->setCellValue("C$xRow", $tripPeriodDataCarrier->getAbLowCount());
            $sheet->getCell("C$xRow")->getHyperlink()->setUrl("$this->context/countedTripPeriods.php?routeNumber=$routeNumber&dateStamps=$requestedDateStampsString&type=ab&percents=$percents&height=low");

            $abLowAverage = $this->calculateAverage($tripPeriodDataCarrier->getAbLowTotal(), $tripPeriodDataCarrier->getAbLowCount());
            $sheet->setCellValue("D$xRow", $abLowAverage);

            $sheet->setCellValue("E$xRow", $tripPeriodDataCarrier->getAbHighCount());
            $sheet->getCell("E$xRow")->getHyperlink()->setUrl("$this->context/countedTripPeriods.php?routeNumber=$routeNumber&dateStamps=$requestedDateStampsString&type=ab&percents=$percents&height=high");

            $abHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getAbHighTotal(), $tripPeriodDataCarrier->getAbHighCount());
            $sheet->setCellValue("F$xRow", $abHighAverage);

            $sheet->setCellValue("G$xRow", $tripPeriodDataCarrier->getAbLowCount() + $tripPeriodDataCarrier->getAbHighCount());
            $sheet->getCell("G$xRow")->getHyperlink()->setUrl("$this->context/countedTripPeriods.php?routeNumber=$routeNumber&dateStamps=$requestedDateStampsString&type=ab&percents=$percents&height=both");


            $abLowHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getAbLowTotal() + $tripPeriodDataCarrier->getAbHighTotal(), $tripPeriodDataCarrier->getAbLowCount() + $tripPeriodDataCarrier->getAbHighCount());
            $sheet->setCellValue("H$xRow", $abLowHighAverage);
            $sheet->setCellValue("I$xRow", $tripPeriodDataCarrier->getAbTripPeriodTimeStandart());
            $xRow++;

            $sheet->setCellValue("B$xRow", "B_A");
            $sheet->setCellValue("C$xRow", $tripPeriodDataCarrier->getBaLowCount());
            $sheet->getCell("C$xRow")->getHyperlink()->setUrl("$this->context/countedTripPeriods.php?routeNumber=$routeNumber&dateStamps=$requestedDateStampsString&type=ba&percents=$percents&height=low");

            $baLowAverage = $this->calculateAverage($tripPeriodDataCarrier->getBaLowTotal(), $tripPeriodDataCarrier->getBaLowCount());
            $sheet->setCellValue("D$xRow", $baLowAverage);

            $sheet->setCellValue("E$xRow", $tripPeriodDataCarrier->getBaHighCount());
            $sheet->getCell("E$xRow")->getHyperlink()->setUrl("$this->context/countedTripPeriods.php?routeNumber=$routeNumber&dateStamps=$requestedDateStampsString&type=ba&percents=$percents&height=high");

            $baHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getBaHighTotal(), $tripPeriodDataCarrier->getBaHighCount());
            $sheet->setCellValue("F$xRow", $baHighAverage);

            $sheet->setCellValue("G$xRow", $tripPeriodDataCarrier->getBaLowCount() + $tripPeriodDataCarrier->getBaHighCount());
            $sheet->getCell("G$xRow")->getHyperlink()->setUrl("$this->context/countedTripPeriods.php?routeNumber=$routeNumber&dateStamps=$requestedDateStampsString&type=ba&percents=$percents&height=both");

            $baLowHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getBaLowTotal() + $tripPeriodDataCarrier->getBaHighTotal(), $tripPeriodDataCarrier->getBaLowCount() + $tripPeriodDataCarrier->getBaHighCount());

            $sheet->setCellValue("H$xRow", $baLowHighAverage);
            $sheet->setCellValue("I$xRow", $tripPeriodDataCarrier->getBaTripPeriodTimeStandart());


            $xRow--;
            $yRow = $xRow + 1;
            $roundTimeScheduled = $tripPeriodDataCarrier->getRoundTimeScheduled();
            $sheet->setCellValue("J$xRow", $roundTimeScheduled);
            $sheet->mergeCells("J$xRow:J$yRow");


            $totalCount = $tripPeriodDataCarrier->getAbLowCount() + $tripPeriodDataCarrier->getAbHighCount() + $tripPeriodDataCarrier->getBaLowCount() + $tripPeriodDataCarrier->getBaHighCount();
            $sheet->setCellValue("K$xRow", $totalCount);
            $sheet->mergeCells("K$xRow:K$yRow");
            if ($abLowHighAverage != "" && $baLowHighAverage != "") {
                $bothLowHighTotal = $this->timeCalculator->getSecondsFromTimeStamp($abLowHighAverage) + $this->timeCalculator->getSecondsFromTimeStamp($baLowHighAverage);
                $bothLowHighAverage = $this->calculateAverage($bothLowHighTotal, 1);
            } else {
                $bothLowHighAverage = "";
            }
            $sheet->setCellValue("L$xRow", $bothLowHighAverage);
            $sheet->mergeCells("L$xRow:L$yRow");
            $sheet->getStyle('J:L')->getAlignment()->setVertical('center');
            $sheet->getStyle('J:L')->getAlignment()->setHorizontal('center');
            $xRow += 2;
        }



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

    //------------
    private function convertColor($colorPlainText) {
        if ($colorPlainText == "white") {
            return "FFFFFF";
        }
        if ($colorPlainText == "red") {
            return "FF0000";
        }
        if ($colorPlainText == "yellow") {
            return "FFFF00";
        }
        if ($colorPlainText == "green") {
            return "008000";
        }
        if ($colorPlainText == "blue") {
            return "0000FF";
        }
        if ($colorPlainText == "lightgrey") {
            return "d3d3d3";
        }
    }

    private function convertDataToArray($date) {
        $dataArray = explode(",", $date);
        if (end($dataArray) == ",") {
            array_pop($dataArray);
        }
        return $dataArray;
    }

//same function i have in countedTripPeriods to show selected routes
    private function lowPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents) {

        $tripPeriodScheduledTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodScheduledTime);
        $tripPeriodActualTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime);
        $difference = $tripPeriodScheduledTimeInSeconds - $tripPeriodActualTimeInSeconds;
        if ($difference >= ($tripPeriodScheduledTimeInSeconds / 100) * (-1 * $percents) &&
                $difference < 0) {
            return true;
        } else {
            return false;
        }
    }

    //same function i have in countedTripPeriods.php to show selected routes
    private function highPercentageChecks($tripPeriodScheduledTime, $tripPeriodActualTime, $percents) {

        $tripPeriodScheduledTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodScheduledTime);
        $tripPeriodActualTimeInSeconds = $this->timeCalculator->getSecondsFromTimeStamp($tripPeriodActualTime);
        $difference = $tripPeriodScheduledTimeInSeconds - $tripPeriodActualTimeInSeconds;
        if ($difference <= ($tripPeriodScheduledTimeInSeconds / 100) * $percents &&
                $difference >= 0) {
            return true;
        } else {
            return false;
        }
    }

    private function calculateAverage($totalSeconds, $count) {
        if ($totalSeconds == "") {
            return "";
        }
        $averageSeconds = $totalSeconds / $count;
        return $this->timeCalculator->getTimeStampFromSeconds($averageSeconds);
    }

}
