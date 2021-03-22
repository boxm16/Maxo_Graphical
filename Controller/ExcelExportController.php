<?php

require 'vendor/autoload.php';
require_once 'TimeCalculator.php';

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
        $sheet->setCellValue('D2', '1* -  A_B გეგმიური გასვლის დრო');
        $sheet->setCellValue('E2', '2* -  A_B ფაქტიური გასვლის დრო ');
        $sheet->setCellValue('F2', '3* -  გასვლის ნომერი');
        $sheet->setCellValue('G2', '4* -  GPS გასვლის ნომერი');
        $sheet->setCellValue('H2', '5* -  A_B GPS გეგმიური გასვლის დრო');
        $sheet->setCellValue('I2', '6* -  A_B ფაქტიური გასვლის დრო');
        $sheet->setCellValue('J2', '-');

        $sheet->setCellValue('K2', 'მძღოლი');
        $sheet->setCellValue('L2', '1* - A_B გეგმიური გასვლის დრო');
        $sheet->setCellValue('M2', '2* -  A_B ფაქტიური გასვლის დრო ');
        $sheet->setCellValue('N2', '3* -  გასვლის ნომერი');
        $sheet->setCellValue('O2', '4* -  GPS გასვლის ნომერი');
        $sheet->setCellValue('P2', '5* -  A_B GPS გეგმიური გასვლის დრო');
        $sheet->setCellValue('Q2', '6* -  A_B ფაქტიური გასვლის დრო');

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

                if ($ab_light == "red" || $ab_light = "yellow") {
                    $middle_light = $ab_light;
                }
                if ($ba_light == "red" || $ba_light = "yellow") {
                    if ($middle_light == "white" || $middle_light == "yellow") {
                        $middle_light = $ba_light;
                    }
                }

                $ab_light = $this->convertColor($ab_light);
                $ba_light = $this->convertColor($ba_light);
                $middle_light = $this->convertColor($middle_light);
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

                $sheet->setCellValue("J$row", "-");
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

                /* "<tr>"
                  . "<td>$routeNumber</td>"
                  . "<td>$dateStamp</td>"
                  . "<td>$ab_driverName</td>"
                  . "<td>$ab_lastTripPeriodStartTimeScheduled</td>"
                  . "<td style=\"background-color:$ab_light\">$ab_lastTripPeriodStartTimeActual</td>"
                  . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$ab_lastTripPeriodType&startTimeScheduled=$ab_lastTripPeriodStartTimeScheduled'  target='_blank'>$ab_lastTripPeriodExodusNumber</a></td>"
                  . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$gps_ab_lastTripPeriodType&startTimeScheduled=$gps_ab_lastTripScheduled'  target='_blank'>$gps_ab_lastTripExodusNumber</a></td>"
                  . "<td>$gps_ab_lastTripScheduled</td>"
                  . "<td>$gps_ab_lastTripActual</td>"
                  . "<td>-</td>"
                  . "<td>$ba_driverName</td>"
                  . "<td>$ba_lastTripPeriodStartTimeScheduled</td>"
                  . "<td style=\"background-color:$ba_light\">$ba_lastTripPeriodStartTimeActual</td>"
                  . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$ba_lastTripPeriodType&startTimeScheduled=$ba_lastTripPeriodStartTimeScheduled'  target='_blank'>$ba_lastTripPeriodExodusNumber</a></td>"
                  . "<td><a href='dayIntervals.php?routeNumber=$routeNumber&dateStamp=$dateStamp&tripPeriodType=$gps_ba_lastTripPeriodType&startTimeScheduled=$gps_ba_lastTripScheduled'  target='_blank'>$gps_ba_lastTripExodusNumber</a></td>"
                  . "<td>$gps_ba_lastTripScheduled</td>"
                  . "<td>$gps_ba_lastTripActual</td>"
                  . "</tr>"; */
            }
            $row++;
        }














        $this->exportFile($spreadsheet);
    }

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

}
