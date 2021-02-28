<?php

require_once 'Controller/RouteXLController.php';
session_start();
if (isset($_POST["routeNumber"])) {
    $_SESSION["routeNumber"] = $_POST["routeNumber"];
    $selectedRouteNumber = $_POST["routeNumber"];
    if (isset($_POST["dates"])) {
        $_SESSION["dates"] = $_POST["dates"];
        $selectedDates = $_POST["dates"];
    } else {
        $emptyDates = array();
        $_SESSION["dates"] = $emptyDates;
        $selectedDates = $emptyDates;
    }
} else {
    if (isset($_SESSION["routeNumber"]) && isset($_SESSION["dates"])) {

        $selectedRouteNumber = $_SESSION["routeNumber"];
        $selectedDates = $_SESSION["dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}
$routeController = new RouteXLController();
$routes = $routeController->getSiftedRoutes($selectedRouteNumber, $selectedDates);


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$spreadsheet->getActiveSheet()->getStyle("A1:N1")->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff696969');

$spreadsheet->getActiveSheet()->getStyle("P1:AD1")->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getStyle('P1:AD1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff696969');


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
$sheet->setCellValue('D1', 'მძღოლი ');
$sheet->setCellValue('E1', 'გასვლის #gegmiuri');
$sheet->setCellValue('F1', 'გასვლის #GPS');
$sheet->setCellValue('G1', 'გასვლის გეგმიური დრო');
$sheet->setCellValue('H1', 'გასვლის ფაქტიური დრო');
$sheet->setCellValue('I1', 'sxvaoba');
$sheet->setCellValue('J1', 'dakarguli dro');
$sheet->setCellValue('K1', 'გეგმიური intervali');
$sheet->setCellValue('L1', 'GPS intervali');
$sheet->setCellValue('M1', 'xarvezi');
$sheet->setCellValue('N1', 'gadascrea');
// B_A
$sheet->setCellValue('P1', 'მარშრუტის #');
$sheet->setCellValue('Q1', 'თარიღი');
$sheet->setCellValue('R1', 'ავტობუსის #');
$sheet->setCellValue('S1', 'მძღოლი ');
$sheet->setCellValue('T1', 'გასვლის #gegmiuri');
$sheet->setCellValue('U1', 'გასვლის #GPS');
$sheet->setCellValue('V1', 'გასვლის გეგმიური დრო');
$sheet->setCellValue('W1', 'გასვლის ფაქტიური დრო');
$sheet->setCellValue('X1', 'sxvaoba');
$sheet->setCellValue('Y1', 'dakarguli dro');
$sheet->setCellValue('Z1', 'გეგმიური intervali');
$sheet->setCellValue('AA1', 'GPS intervali');
$sheet->setCellValue('AB1', 'xarvezi');
$sheet->setCellValue('AC1', 'gadascrea');


$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '#000000'],
        ],
    ],
];

$spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("B1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("D1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("E1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("F1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("G1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("H1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("I1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("J1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("K1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("L1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("M1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("N1")->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getStyle("P1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("Q1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("R1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("S1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("T1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("U1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("V1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("W1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("X1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("Y1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("Z1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("AA1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("AB1")->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle("AC1")->applyFromArray($styleArray);

//endof header
//now body

$dayStartIndex = 2;
$dayEndIndex = 2;

foreach ($routes as $route) {

    $days = $route->getDays();
    foreach ($days as $day) {
        $row = $dayStartIndex;
        $intervals = $day->getIntervals();
        $scheduledIntervals = $intervals["scheduledIntervals"];
        $gpsIntervals = $intervals["gpsIntervals"];
        //----A-B shceduled
        $abDirectionScheduled = $scheduledIntervals[0];
        foreach ($abDirectionScheduled as $tripPeriod) {
            $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
            $sheet->setCellValue("E$row", $exodusNumber);
            $row++;
        }
        $dayEndIndex = $row;

        //-----------A-B intervals
        $abDirectionGPS = $gpsIntervals[0];
        $row = $dayStartIndex;
        foreach ($abDirectionGPS as $tripPeriod) {
            $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
            $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
            $busNumber = $tripPeriod->getTripPeriodDNA()->getBusNumber();
            $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
            $driverName = $tripPeriod->getTripPeriodDNA()->getDriverName();
            //   $tripPeriodType = $tripPeriod->getTypeGe();
            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
            $startTimeActual = $tripPeriod->getStartTimeActual();
            $startTimeDifference = $tripPeriod->getStartTimeDifference();
            $startTimeDifferenceColor = convertColor($tripPeriod->getStartTimeDifferenceColor());

            $lostTime = $tripPeriod->getLostTime();
            $lostTimeColor = convertColor($tripPeriod->getLightsForLostTime());

            $scheduledInterval = $tripPeriod->getScheduledInterval();
            $scheduledIntervaColor = convertColor($tripPeriod->getScheduledIntervalColor());

            $gpsBasedActualInterval = $tripPeriod->getGpsBasedActualInterval();
            $gpsBasedActualIntervalColor = convertColor($tripPeriod->getGpsBasedActualIntervalColor());

            //this line makes time to be seen in excel when i need to calculate average fo selected cells. but its not working for timestamp with "-" sign

            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());



            $blackSpot = $tripPeriod->getGPSBlackSpot();
            $blackSpotColor = convertColor("white");
            if ($blackSpot != "") {
                $blackSpotColor = convertColor("green");
            }

            $gSpot = $tripPeriod->getGSpot();
            $gSpotColor = convertColor("white");
            if ($gSpot != "") {
                $gSpotColor = convertColor("green");
            }


            //---filling cells
            $sheet->setCellValue("A$row", $routeNumber);
            $sheet->setCellValue("B$row", $dateStamp);
            $sheet->setCellValue("C$row", $busNumber);
            $sheet->setCellValue("D$row", $driverName);

            $sheet->setCellValue("F$row", $exodusNumber);
            $sheet->setCellValue("G$row", $startTimeScheduled);
            $sheet->setCellValue("H$row", $startTimeActual);
            $sheet->setCellValue("I$row", $startTimeDifference);
            $spreadsheet->getActiveSheet()->getStyle("I$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($startTimeDifferenceColor);

            $sheet->setCellValue("J$row", $lostTime);
            $spreadsheet->getActiveSheet()->getStyle("J$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($lostTimeColor);

            $sheet->setCellValue("K$row", $scheduledInterval);
            $sheet->setCellValue("L$row", $gpsBasedActualInterval);
            $spreadsheet->getActiveSheet()->getStyle("L$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($gpsBasedActualIntervalColor);

            $sheet->setCellValue("M$row", $blackSpot);
            $spreadsheet->getActiveSheet()->getStyle("M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($blackSpotColor);

            $sheet->setCellValue("N$row", $gSpot);
            $spreadsheet->getActiveSheet()->getStyle("N$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($gSpotColor);


            $row++;
        }
        if ($dayEndIndex < $row) {
            $dayEndIndex = $row;
        }

        //----B-A shceduled
        $baDirectionScheduled = $scheduledIntervals[1];
        $row = $dayStartIndex;
        foreach ($baDirectionScheduled as $tripPeriod) {
            $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
            $sheet->setCellValue("T$row", $exodusNumber);
            $row++;
        }
        if ($dayEndIndex < $row) {
            $dayEndIndex = $row;
        }


        //-----------B-A intervals
        $baDirectionGPS = $gpsIntervals[1];
        $row = $dayStartIndex;
        foreach ($baDirectionGPS as $tripPeriod) {
            $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
            $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
            $busNumber = $tripPeriod->getTripPeriodDNA()->getBusNumber();
            $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
            $driverName = $tripPeriod->getTripPeriodDNA()->getDriverName();
            //   $tripPeriodType = $tripPeriod->getTypeGe();
            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
            $startTimeActual = $tripPeriod->getStartTimeActual();
            $startTimeDifference = $tripPeriod->getStartTimeDifference();
            $startTimeDifferenceColor = convertColor($tripPeriod->getStartTimeDifferenceColor());

            $lostTime = $tripPeriod->getLostTime();
            $lostTimeColor = convertColor($tripPeriod->getLightsForLostTime());

            $scheduledInterval = $tripPeriod->getScheduledInterval();
            $scheduledIntervaColor = convertColor($tripPeriod->getScheduledIntervalColor());

            $gpsBasedActualInterval = $tripPeriod->getGpsBasedActualInterval();
            $gpsBasedActualIntervalColor = convertColor($tripPeriod->getGpsBasedActualIntervalColor());

            //this line makes time to be seen in excel when i need to calculate average fo selected cells. but its not working for timestamp with "-" sign

            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());



            $blackSpot = $tripPeriod->getGPSBlackSpot();
            $blackSpotColor = convertColor("white");
            if ($blackSpot != "") {
                $blackSpotColor = convertColor("green");
            }

            $gSpot = $tripPeriod->getGSpot();
            $gSpotColor = convertColor("white");
            if ($gSpot != "") {
                $gSpotColor = convertColor("green");
            }


            //---filling cells
            $sheet->setCellValue("P$row", $routeNumber);
            $sheet->setCellValue("Q$row", $dateStamp);
            $sheet->setCellValue("R$row", $busNumber);
            $sheet->setCellValue("S$row", $driverName);

            $sheet->setCellValue("U$row", $exodusNumber);
            $sheet->setCellValue("V$row", $startTimeScheduled);
            $sheet->setCellValue("W$row", $startTimeActual);
            $sheet->setCellValue("X$row", $startTimeDifference);
            $spreadsheet->getActiveSheet()->getStyle("X$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($startTimeDifferenceColor);

            $sheet->setCellValue("Y$row", $lostTime);
            $spreadsheet->getActiveSheet()->getStyle("Y$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($lostTimeColor);

            $sheet->setCellValue("Z$row", $scheduledInterval);
            $sheet->setCellValue("AA$row", $gpsBasedActualInterval);
            $spreadsheet->getActiveSheet()->getStyle("AA$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($gpsBasedActualIntervalColor);

            $sheet->setCellValue("AB$row", $blackSpot);
            $spreadsheet->getActiveSheet()->getStyle("AB$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($blackSpotColor);

            $sheet->setCellValue("AC$row", $gSpot);
            $spreadsheet->getActiveSheet()->getStyle("AC$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($gSpotColor);


            $row++;
        }
        if ($dayEndIndex < $row) {
            $dayEndIndex = $row;
        }

        //drowing day_ending line

        $styleArray = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];

        $sheet->getStyle("A$dayEndIndex:AE$dayEndIndex")->applyFromArray($styleArray);


        //-------- shifting dayStartIndex;
        $dayStartIndex = $dayEndIndex;
    }
}






$filename = 'tmps/' . time() . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);


header('Content-Type: application/x-www-form-urlencoded');

header('Content-Transfer-Encoding: Binary');

header("Content-disposition: attachment; filename=\"" . $filename . "\"");

readfile($filename);

unlink($filename);

function convertColor($colorPlainText) {
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
}
