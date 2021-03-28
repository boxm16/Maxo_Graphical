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
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(13);


        $spreadsheet->getActiveSheet()->getStyle("A1:Q2")->getFont()->setSize(14);



        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D2:I2')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('L2:Q2')->getAlignment()->setWrapText(true);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'A_B');
        $sheet->setCellValue('K1', 'B_A');
        $sheet->setCellValue('A2', 'მარშ. #');
        $sheet->setCellValue('B2', 'თარიღი');
        $sheet->setCellValue('C2', 'მძღოლი');
        $sheet->setCellValue('D2', 'გეგმიური გასვლის დრო');
        $sheet->setCellValue('E2', 'ფაქტიური გასვლის დრო ');
        $sheet->setCellValue('F2', 'გასვლის ნომერი');
        $sheet->setCellValue('G2', 'GPS გასვლის ნომერი');
        $sheet->setCellValue('H2', 'GPS გეგმიური გასვლის დრო');
        $sheet->setCellValue('I2', 'ფაქტიური გასვლის დრო');
        //  $sheet->setCellValue('J2', '=SUBTOTAL(9,J3:J114)'); THIS IS WRITTEN AT THE END OF THIS FUNCTION, TO SEE WHAT ROW IS LAST

        $sheet->setCellValue('K2', 'მძღოლი');
        $sheet->setCellValue('L2', 'გეგმიური გასვლის დრო');
        $sheet->setCellValue('M2', 'ფაქტიური გასვლის დრო ');
        $sheet->setCellValue('N2', 'გასვლის ნომერი');
        $sheet->setCellValue('O2', 'GPS გასვლის ნომერი');
        $sheet->setCellValue('P2', 'GPS გეგმიური გასვლის დრო');
        $sheet->setCellValue('Q2', 'ფაქტიური გასვლის დრო');

        $sheet->getStyle('A:S')->getAlignment()->setHorizontal('center'); //this align all cell texts to center
        $sheet->mergeCells("A1:I1");
        $sheet->mergeCells("K1:S1");
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
                $sheet->setCellValue("J$row", "0");
                if (($ab_light == "yellow" || $ba_light == "yellow")) {
                    $middle_light = "yellow";
                }
                if ($ab_light == "red" || $ba_light == "red") {
                    $middle_light = "red";
                    $sheet->setCellValue("J$row", "1");
                }
                if ($ab_light == "red" && $ba_light == "red") {
                    $middle_light = $ab_light;
                    $sheet->setCellValue("J$row", "2");
                }


                $ab_light = $this->convertColor($ab_light);
                $ba_light = $this->convertColor($ba_light);
                $middle_light = $this->convertColor($middle_light);
                $sheet->setCellValue("A$row", $routeNumber);
                $sheet->setCellValue("B$row", $dateStamp);
                $sheet->setCellValue("C$row", $ab_driverName);
                $sheet->setCellValue("D$row", $ab_lastTripPeriodStartTimeScheduled);
                $sheet->setCellValue("E$row", $ab_lastTripPeriodStartTimeActual);

                $sheet->getStyle("E$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($ab_light);

                $sheet->setCellValue("F$row", $ab_lastTripPeriodExodusNumber);
                $tripPeriodType = "ab";
                $sheet->getCell("F$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$ab_lastTripPeriodStartTimeScheduled");

                $sheet->setCellValue("G$row", $gps_ab_lastTripExodusNumber);
                $sheet->getCell("G$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$gps_ab_lastTripExodusNumber");

                $sheet->setCellValue("H$row", $gps_ab_lastTripScheduled);
                $sheet->setCellValue("I$row", $gps_ab_lastTripActual);


                $sheet->getStyle("J$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($middle_light);


                $sheet->setCellValue("K$row", $ba_driverName);
                $sheet->setCellValue("L$row", $ba_lastTripPeriodStartTimeScheduled);
                $sheet->setCellValue("M$row", $ba_lastTripPeriodStartTimeActual);


                $spreadsheet->getActiveSheet()->getStyle("M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($ba_light);

                $sheet->setCellValue("N$row", $ba_lastTripPeriodExodusNumber);
                $tripPeriodType = "ba";
                $sheet->getCell("N$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$ba_lastTripPeriodStartTimeScheduled");

                $sheet->setCellValue("O$row", $gps_ba_lastTripExodusNumber);
                $sheet->getCell("O$row")->getHyperlink()->setUrl("$this->context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$gps_ba_lastTripExodusNumber");

                $sheet->setCellValue("P$row", $gps_ba_lastTripScheduled);
                $sheet->setCellValue("Q$row", $gps_ba_lastTripActual);

                $aPoint = $routePoints[$routeNumber]->getAPoint();
                $bPoint = $routePoints[$routeNumber]->getBPoint();
                $sheet->setCellValue("R$row", $aPoint);
                $sheet->setCellValue("S$row", $bPoint);

                $row++;
            }
        }

        $sheet->setCellValue('J2', "=SUBTOTAL(9,J3:J$row)");

        $this->exportFile($spreadsheet);
    }

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
                                                                    $tripPeriodDataCarrier->setAbTripPeriodTimeStandart("multi standart");
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
                                                                    $tripPeriodDataCarrier->setBaTripPeriodTimeStandart("multi standart");
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
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('C')->setWidth(7);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(7);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('G')->setWidth(7);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(13);
        $sheet->getColumnDimension('J')->setWidth(13);
        $sheet->getColumnDimension('K')->setWidth(13);
        $sheet->getStyle('A1:K1')->getAlignment()->setWrapText(true);

        $sheet->setCellValue('A1', 'მარშრუტის #');
        $sheet->setCellValue('B1', 'მიმართულება');
        $sheet->setCellValue('C1', '-პროცენტი<=X<0 ჩათვლილი ბრუნები ჩაოდენობა');
        $sheet->setCellValue('D1', '-პროცენტი<=X<0 ჩათვლილი ბრუნების საშუალო ფაქტიური დრო');
        $sheet->setCellValue('E1', '0<=X<პროცენტი ჩათვლილი ბრუნები ჩაოდენობა');
        $sheet->setCellValue('F1', '0<=X<პროცენტი ჩათვლილი ბრუნების საშუალო ფაქტიური დრო');
        $sheet->setCellValue('G1', 'ყველა ჩათვლილი ბრუნები ჩაოდენობა');
        $sheet->setCellValue('H1', 'ყველა ჩათვლილი ბრუნების საშუალო ფაქტიური დრო');
        $sheet->setCellValue('I1', 'ყველა ჩათვლილი ბრუნების სტანდარტული გეგმიური დრო');
        $sheet->setCellValue('J1', 'ყველა ჩათვლილი ბრუნების  სტანდარტული გეგმიური დრო');
        $sheet->setCellValue('K1', 'ორივე მიმართულების ბრუნების საშუალო ფაქტიური დრო');

        $sheet->getStyle('A')->getAlignment()->setVertical('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $xRow = 2;
        $yRow;
        foreach ($calculationMap as $tripPeriodDataCarrier) {
            $routeNumber = $tripPeriodDataCarrier->getRouteNumber();
            $sheet->setCellValue("A$xRow", $routeNumber);
            $yRow = $xRow + 1;
            $sheet->mergeCells("A$xRow:A$yRow");
            $sheet->setCellValue("B$xRow", "A_B");

            $sheet->setCellValue("C$xRow", $tripPeriodDataCarrier->getAbLowCount());
            $abLowAverage = $this->calculateAverage($tripPeriodDataCarrier->getAbLowTotal(), $tripPeriodDataCarrier->getAbLowCount());
            $sheet->setCellValue("D$xRow", $abLowAverage);

            $sheet->setCellValue("E$xRow", $tripPeriodDataCarrier->getAbHighCount());
            $abHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getAbHighTotal(), $tripPeriodDataCarrier->getAbHighCount());
            $sheet->setCellValue("F$xRow", $abHighAverage);

            $sheet->setCellValue("G$xRow", $tripPeriodDataCarrier->getAbLowCount() + $tripPeriodDataCarrier->getAbHighCount());


            $abLowHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getAbLowTotal() + $tripPeriodDataCarrier->getAbHighTotal(), $tripPeriodDataCarrier->getAbLowCount() + $tripPeriodDataCarrier->getAbHighCount());
            $sheet->setCellValue("H$xRow", $abLowHighAverage);
            $sheet->setCellValue("I$xRow", $tripPeriodDataCarrier->getAbTripPeriodTimeStandart());
            $xRow++;

            $sheet->setCellValue("B$xRow", "B_A");
            $sheet->setCellValue("C$xRow", $tripPeriodDataCarrier->getBaLowCount());
            $baLowAverage = $this->calculateAverage($tripPeriodDataCarrier->getBaLowTotal(), $tripPeriodDataCarrier->getBaLowCount());
            $sheet->setCellValue("D$xRow", $baLowAverage);

            $sheet->setCellValue("E$xRow", $tripPeriodDataCarrier->getBaHighCount());
            $baHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getBaHighTotal(), $tripPeriodDataCarrier->getBaHighCount());
            $sheet->setCellValue("F$xRow", $baHighAverage);

            $sheet->setCellValue("G$xRow", $tripPeriodDataCarrier->getBaLowCount() + $tripPeriodDataCarrier->getBaHighCount());

            $baLowHighAverage = $this->calculateAverage($tripPeriodDataCarrier->getBaLowTotal() + $tripPeriodDataCarrier->getBaHighTotal(), $tripPeriodDataCarrier->getBaLowCount() + $tripPeriodDataCarrier->getBaHighCount());

            $sheet->setCellValue("H$xRow", $baLowHighAverage);
            $sheet->setCellValue("I$xRow", $tripPeriodDataCarrier->getBaTripPeriodTimeStandart());


            $xRow--;
            $totalCount = $tripPeriodDataCarrier->getAbLowCount() + $tripPeriodDataCarrier->getAbHighCount() + $tripPeriodDataCarrier->getBaLowCount() + $tripPeriodDataCarrier->getBaHighCount();
            $sheet->setCellValue("J$xRow", $totalCount);
            $yRow = $xRow + 1;
            $sheet->mergeCells("J$xRow:J$yRow");
            if ($abLowHighAverage != "" && $baLowHighAverage != "") {
                $bothLowHighTotal = $this->timeCalculator->getSecondsFromTimeStamp($abLowHighAverage) + $this->timeCalculator->getSecondsFromTimeStamp($baLowHighAverage);
                $bothLowHighAverage = $this->calculateAverage($bothLowHighTotal, 1);
            } else {
                $bothLowHighAverage = "";
            }
            $sheet->setCellValue("K$xRow", $bothLowHighAverage);
            $sheet->mergeCells("K$xRow:K$yRow");
            $sheet->getStyle('J:K')->getAlignment()->setVertical('center');
            $sheet->getStyle('J:K')->getAlignment()->setHorizontal('center');
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
