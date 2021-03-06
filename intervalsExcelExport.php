<?php

require_once 'Controller/RouteXLController.php';
require_once 'clientId.php';
session_start();
if (isset($_POST["routes:dates"])) {
    $_SESSION["routes:dates"] = $_POST["routes:dates"];
    $requestedRoutesAndDates = $_POST["routes:dates"];
} else {
    if (isset($_SESSION["routes:dates"])) {

        $requestedRoutesAndDates = $_SESSION["routes:dates"];
    } else {
        header("Location:errorPage.php");
        exit;
    }
}


$routeController = new RouteXLController();
$routes = $routeController->getSiftedRoutes($clientId, $requestedRoutesAndDates);

//this thing-context, i need to write hyperlinks
$context = "http://berishvili.eu5.org";
$server = $_SERVER['SERVER_NAME'];
if ($server == "localhost") {
    $context = "http://localhost/Maxo_Graphical";
}


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
//--A-B
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('E1')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('F1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('I1')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('J1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('M1')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('N1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(3);

$spreadsheet->getActiveSheet()->getStyle('G1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('H1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('K1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('L1')->getAlignment()->setWrapText(true);
//---------B-A

$spreadsheet->getActiveSheet()->getStyle('P1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('R1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('T1')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('U1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('X1')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Y1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getStyle('AB1')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AC1')->getAlignment()->setTextRotation(90);

$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(3);
$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setWidth(3);

$spreadsheet->getActiveSheet()->getStyle('V1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('W1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('Z1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('AA1')->getAlignment()->setWrapText(true);


//-------both--

$spreadsheet->getActiveSheet()->getStyle("A1:N1")->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff696969');

$spreadsheet->getActiveSheet()->getStyle("P1:AC1")->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getStyle('P1:AC1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff696969');


$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', '??????????????????????????? #');
$sheet->setCellValue('B1', '??????????????????');
$sheet->setCellValue('C1', '??????????????????????????? #');
$sheet->setCellValue('D1', '??????????????????');
$sheet->setCellValue('E1', '????????????????????? #(????????????)');
$sheet->setCellValue('F1', '????????????????????? #(GPS)');
$sheet->setCellValue('G1', '????????????????????? ???????????????????????? ?????????');
$sheet->setCellValue('H1', '????????????????????? ???????????????????????? ?????????');
$sheet->setCellValue('I1', '?????????????????????');
$sheet->setCellValue('J1', '??????????????????????????? ?????????');
$sheet->setCellValue('K1', '???????????????????????? ???????????????????????????');
$sheet->setCellValue('L1', 'GPS ???????????????????????????');
$sheet->setCellValue('M1', '?????????????????????');
$sheet->setCellValue('N1', '??????????????????????????????');
// B_A
$sheet->setCellValue('P1', '??????????????????????????? #');
$sheet->setCellValue('Q1', '??????????????????');
$sheet->setCellValue('R1', '??????????????????????????? #');
$sheet->setCellValue('S1', '??????????????????');
$sheet->setCellValue('T1', '????????????????????? #????????????)');
$sheet->setCellValue('U1', '????????????????????? #(GPS)');
$sheet->setCellValue('V1', '????????????????????? ???????????????????????? ?????????');
$sheet->setCellValue('W1', '????????????????????? ???????????????????????? ?????????');
$sheet->setCellValue('X1', '?????????????????????');
$sheet->setCellValue('Y1', '??????????????????????????? ?????????');
$sheet->setCellValue('Z1', '???????????????????????? ???????????????????????????');
$sheet->setCellValue('AA1', 'GPS ???????????????????????????');
$sheet->setCellValue('AB1', '?????????????????????');
$sheet->setCellValue('AC1', '??????????????????????????????');


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


foreach ($routes as $route) {

    $days = $route->getDays();
    foreach ($days as $day) {
        $row = $dayStartIndex;
        $intervals = $day->getIntervals();
        $scheduledIntervals = $intervals["scheduledIntervals"];
        $gpsIntervals = $intervals["gpsIntervals"];
        $abDirectionScheduled = $scheduledIntervals[0];
        $abDirectionGPS = $gpsIntervals[0];
        $baDirectionScheduled = $scheduledIntervals[1];
        $baDirectionGPS = $gpsIntervals[1];

        //here i find out which of those arrays are th longes
        $dayEndIndex = $dayStartIndex + findLongesArray($abDirectionScheduled, $abDirectionGPS, $baDirectionScheduled, $baDirectionGPS);

//--center
        while ($row < $dayEndIndex) {
            $sheet->setCellValue("O$row", ".");
            $row++;
        }


//----A-B shceduled
        $row = $dayStartIndex;
        foreach ($abDirectionScheduled as $tripPeriod) {
            $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
            $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
            $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
            $sheet->getCell("E$row")->getHyperlink()->setUrl("$context/exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled");
            $sheet->setCellValue("E$row", $exodusNumber);
            $row++;
        }
        while ($row < $dayEndIndex) {
            $sheet->setCellValue("E$row", ".");
            $row++;
        }

        //-----------A-B intervals

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
                markProblemPointInCenter($spreadsheet, $row);
                $blackSpotColor = convertColor("green");
            }

            $gSpot = $tripPeriod->getGSpot();
            $gSpotColor = convertColor("white");
            if ($gSpot != "") {
                markProblemPointInCenter($spreadsheet, $row);
                $gSpotColor = convertColor("green");
            }


            //---filling cells
            $sheet->setCellValue("A$row", $routeNumber);
            $sheet->setCellValue("B$row", $dateStamp);
            $sheet->setCellValue("C$row", $busNumber);
            $sheet->setCellValue("D$row", $driverName);

            $sheet->getCell("F$row")->getHyperlink()->setUrl("$context/exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled");
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
        while ($row < $dayEndIndex) {
            $sheet->setCellValue("A$row", ".");
            $sheet->setCellValue("B$row", ".");
            $sheet->setCellValue("C$row", ".");
            $sheet->setCellValue("D$row", ".");

            $sheet->setCellValue("F$row", ".");
            $sheet->setCellValue("G$row", ".");
            $sheet->setCellValue("H$row", ".");
            $sheet->setCellValue("I$row", ".");

            $sheet->setCellValue("J$row", ".");

            $sheet->setCellValue("K$row", ".");
            $sheet->setCellValue("L$row", ".");

            $sheet->setCellValue("M$row", ".");

            $sheet->setCellValue("N$row", ".");

            $row++;
        }

        //----B-A shceduled

        $row = $dayStartIndex;
        foreach ($baDirectionScheduled as $tripPeriod) {
            $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
            $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
            $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();

            $sheet->getCell("T$row")->getHyperlink()->setUrl("$context/exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled");

            $sheet->setCellValue("T$row", $exodusNumber);
            $row++;
        }
        while ($row < $dayEndIndex) {
            $sheet->setCellValue("T$row", ".");
            $row++;
        }


        //-----------B-A intervals

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
                markProblemPointInCenter($spreadsheet, $row);
                $blackSpotColor = convertColor("green");
            }

            $gSpot = $tripPeriod->getGSpot();
            $gSpotColor = convertColor("white");
            if ($gSpot != "") {
                markProblemPointInCenter($spreadsheet, $row);
                $gSpotColor = convertColor("green");
            }


            //---filling cells
            $sheet->setCellValue("P$row", $routeNumber);
            $sheet->setCellValue("Q$row", $dateStamp);
            $sheet->setCellValue("R$row", $busNumber);
            $sheet->setCellValue("S$row", $driverName);

            $sheet->getCell("U$row")->getHyperlink()->setUrl("$context/exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled");
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
        while ($row < $dayEndIndex) {
            $sheet->setCellValue("P$row", ".");
            $sheet->setCellValue("Q$row", ".");
            $sheet->setCellValue("R$row", ".");
            $sheet->setCellValue("S$row", ".");

            $sheet->setCellValue("U$row", ".");
            $sheet->setCellValue("V$row", ".");
            $sheet->setCellValue("W$row", ".");
            $sheet->setCellValue("X$row", ".");

            $sheet->setCellValue("Y$row", ".");

            $sheet->setCellValue("Z$row", ".");
            $sheet->setCellValue("AA$row", ".");

            $sheet->setCellValue("AB$row", ".");

            $sheet->setCellValue("AC$row", ".");

            $row++;
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
    if ($colorPlainText == "blue") {
        return "0000FF";
    }
}

function findLongesArray($arr_1, $arr_2, $arr_3, $arr_4) {
    return max(count($arr_1), count($arr_2), count($arr_3), count($arr_4));
}

function markProblemPointInCenter($spreadsheet, $row) {
    $problemPointColor = convertColor("blue");
    $spreadsheet->getActiveSheet()->getStyle("O$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($problemPointColor);
}
