<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportController {

    public function exportGuaranteedTripPeriods($routes) {



        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'მარშრუტის #');


        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],
            ],
        ];

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

}
