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

$spreadsheet->getActiveSheet()->getStyle("A1:M1")->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffc0cb');

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

//endof header
//now body

$row = 2;
foreach ($routes as $route) {
    $days = $route->getDays();
    foreach ($days as $day) {
        $exoduses = $day->getExoduses();
        foreach ($exoduses as $exodus) {
            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {
                $tripPeriods = $tripVoucher->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $routeNumber = $tripPeriod->getTripPeriodDNA()->getRouteNumber();
                    $dateStamp = $tripPeriod->getTripPeriodDNA()->getDateStamp();
                    $busNumber = $tripPeriod->getTripPeriodDNA()->getBusNumber();
                    $exodusNumber = $tripPeriod->getTripPeriodDNA()->getExodusNumber();
                    $driverName = $tripPeriod->getTripPeriodDNA()->getDriverName();
                    $tripPeriodType = $tripPeriod->getTypeGe();
                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                    $startTimeActual = $tripPeriod->getStartTimeActual();
                    $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                    $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();

                    $tripPeriodScheduledTime = $tripPeriod->getTripPeriodScheduledTime();
                    $tripPeriodActualTime = $tripPeriod->getTripPeriodActualTime();
                    $tripPeriodDifferenceTime = $tripPeriod->getTripPeriodDifferenceTime();
                    $tripPeriodDifferenceTimeColor = $tripPeriod->getTripPeriodDifferenceTimeColor();
                    if ($tripPeriodDifferenceTimeColor == "white") {
                        $tripPeriodDifferenceTimeColor = "FFFFFF";
                    }
                    if ($tripPeriodDifferenceTimeColor == "red") {
                        $tripPeriodDifferenceTimeColor = "FF0000";
                    }
                    if ($tripPeriodDifferenceTimeColor == "yellow") {
                        $tripPeriodDifferenceTimeColor = "FFFF00";
                    }
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

                    $spreadsheet->getActiveSheet()->getStyle("A$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("B$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("C$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("D$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("E$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("F$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("G$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("H$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("I$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("J$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("K$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("L$row")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("M$row")->applyFromArray($styleArray);

                    $spreadsheet->getActiveSheet()->getStyle("M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($tripPeriodDifferenceTimeColor);

//   . "<td style=\"width:100px;background-color:$tripPeriodDifferenceTimeColor\">$tripPeriodDifferenceTime</td>"
                    $row++;
                }
            }
        }
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
