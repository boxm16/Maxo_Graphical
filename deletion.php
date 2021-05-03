<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save("reports/walupa.xlsx");

$s = microtime(true);


echo"<hr>";



$e = microtime(true);
echo ($e - $s);
