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

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(13);

$spreadsheet->getActiveSheet()->getStyle("A1:P1")->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF');

$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('E1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('F1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('I1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('J1')->getAlignment()->setWrapText(true);

$spreadsheet->getActiveSheet()->getStyle('L1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('M1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('N1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('O1')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);




$sheet = $spreadsheet->getActiveSheet();

$sheet->getStyle('A:P')->getAlignment()->setHorizontal('center'); //this align all cell texts to center

$sheet->setCellValue('A1', 'გეგმიუირი გასვლის დრო');
$sheet->setCellValue('B1', 'ფაქტიური გასვლის დრო');
$sheet->setCellValue('C1', 'სხვაობა');
$sheet->setCellValue('D1', 'მიმართულება');
$sheet->setCellValue('E1', 'გეგმიუირი მისვლის დრო');
$sheet->setCellValue('F1', 'ფაქტიური მისვლის დრო');
$sheet->setCellValue('G1', 'სხვაობა');
$sheet->setCellValue('H1', 'link');
$sheet->setCellValue('I1', 'წირის გეგმიური დრო');
$sheet->setCellValue('J1', 'წირის ფაქტიური დრო');
$sheet->setCellValue('K1', 'სხვაობა');
$sheet->setCellValue('L1', 'დგომის გეგმიური დრო');
$sheet->setCellValue('M1', 'დგომის ფაქტიური დრო');
$sheet->setCellValue('N1', 'დაკარგული დრო');
$sheet->setCellValue('O1', 'GPS ინტერ/ლი');
$sheet->setCellValue('P1', 'ინტე/ლების ლინკი');






//endof header
//now body

$row = 2;

foreach ($routes as $route) {
    $routeNumber = $route->getNumber();
    $sheet->setCellValue("A$row", "მარშრუტა #: " . $routeNumber);
    $spreadsheet->getActiveSheet()->mergeCells("A$row:P$row");

    $row++;

    $days = $route->getDays();
    foreach ($days as $day) {
        $dateStamp = $day->getDateStamp();
        $sheet->setCellValue("A$row", "თარიღი: " . $dateStamp);
        $spreadsheet->getActiveSheet()->mergeCells("A$row:P$row");
        $row++;

        $day->getIntervals(); //here I actially set Intervals
        $exoduses = $day->getExoduses();
        foreach ($exoduses as $exodus) {
            $exodusNumber = $exodus->getNumber();
            $sheet->setCellValue("A$row", "გასვლა #: " . $exodusNumber);
            $spreadsheet->getActiveSheet()->mergeCells("A$row:P$row");
            $row++;

            $tripVouchers = $exodus->getTripVouchers();
            foreach ($tripVouchers as $tripVoucher) {

                $tripVouvherNumber = $tripVoucher->getNumber();
                $notes = $tripVoucher->getNotes();

                $text = "მარშრუტა #: " . $routeNumber .
                        "თარიღი: " . $dateStamp .
                        "გასვლა #: " . $exodusNumber .
                        "საგზური #: " . $tripVouvherNumber .
                        "შენიშვნები: " . $notes;



                $sheet->setCellValue("A$row", $text);
                $spreadsheet->getActiveSheet()->mergeCells("A$row:P$row");
                $spreadsheet->getActiveSheet()->getStyle("A$row:P$row")->getAlignment()->setWrapText(true);
                /*   $width = 120;
                  $height = 20;
                  if (strlen($text) > $width) {
                  $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight(ceil(strlen($text) / $width) * $height);
                  }
                 */

                $row++;

                $tripPeriods = $tripVoucher->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                    $startTimeActual = $tripPeriod->getStartTimeActual();
                    $startTimeDifference = $tripPeriod->getStartTimeDifference();
                    $tripPeriodType = $tripPeriod->getType();
                    $tripPeriodTypeGe = $tripPeriod->getTypeGe();
                    $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                    $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                    $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();
                    $tripPeriodScheduledTime = $tripPeriod->getTripPeriodScheduledTime();
                    $tripPeriodActualTime = $tripPeriod->getTripPeriodActualTime();
                    $tripPeriodDifferenceTime = $tripPeriod->getTripPeriodDifferenceTime();

                    $haltTimeScheduled = $tripPeriod->getHaltTimeScheduled();
                    $haltTimeActual = $tripPeriod->getHaltTimeActual();
                    $lostTime = $tripPeriod->getLostTime();

                    $gpsBasedActualInterval = $tripPeriod->getGpsBasedActualInterval();




                    $rowColor = "white";
                    if ($tripPeriod->getType() == "break") {
                        $rowColor = "lightgrey";
                        if ($startTimeDifferenceLights == "white") {
                            $startTimeDifferenceLights = "lightgrey";
                        }
                        if ($arrivalTimeDifferenceLights == "white") {
                            $arrivalTimeDifferenceLights = "lightgrey";
                        }
                    }
                    $rowColor = convertColor($rowColor);
                    $lostTimeLights = convertColor($tripPeriod->getLightsForLostTime());
                    $startTimeDifferenceLights = convertColor($tripPeriod->getStartTimeDifferenceColor());
                    $arrivalTimeDifferenceLights = convertColor($tripPeriod->getArrivalTimeDifferenceColor());



                    $tripPeriodDifferenceTimeLights = convertColor($tripPeriod->getTripPeriodDifferenceTimeColor());


                    //cell filling
                    $spreadsheet->getActiveSheet()->getStyle("A$row:P$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rowColor);



                    $sheet->setCellValue("A$row", $startTimeScheduled);
                    $sheet->setCellValue("B$row", $startTimeActual);
                    $sheet->setCellValue("C$row", $startTimeDifference);
                    $spreadsheet->getActiveSheet()->getStyle("C$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($startTimeDifferenceLights);


                    $sheet->setCellValue("D$row", $tripPeriodTypeGe);
                    $sheet->setCellValue("E$row", $arrivalTimeScheduled);
                    $sheet->setCellValue("F$row", $arrivalTimeActual);
                    $sheet->setCellValue("G$row", $arrivalTimeDifference);
                    $spreadsheet->getActiveSheet()->getStyle("G$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($arrivalTimeDifferenceLights);


                    $sheet->getCell("H$row")->getHyperlink()->setUrl("$context/exodus.php?routeNumber=$routeNumber&dateStamp=$dateStamp&exodusNumber=$exodusNumber&startTimeScheduled=$startTimeScheduled");
                    $sheet->setCellValue("H$row", "link");

                    $sheet->setCellValue("I$row", $tripPeriodScheduledTime);
                    $sheet->setCellValue("J$row", $tripPeriodActualTime);
                    $sheet->setCellValue("K$row", $tripPeriodDifferenceTime);
                    $spreadsheet->getActiveSheet()->getStyle("K$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($tripPeriodDifferenceTimeLights);

                    $sheet->setCellValue("L$row", $haltTimeScheduled);
                    $sheet->setCellValue("M$row", $haltTimeActual);
                    $sheet->setCellValue("N$row", $lostTime);
                    $spreadsheet->getActiveSheet()->getStyle("N$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($lostTimeLights);

                    $sheet->setCellValue("O$row", $gpsBasedActualInterval);

                    $sheet->getCell("P$row")->getHyperlink()->setUrl("$context/dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$tripPeriodType&startTimeScheduled=$startTimeScheduled");
                    $sheet->setCellValue("P$row", "O");

                    $row++;
                }
            }
        }
    }
}












//--------------------------------
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
    if ($colorPlainText == "lightgrey") {
        return "d3d3d3";
    }
}
