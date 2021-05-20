<?php

require_once "SimpleXLSX.php";
require_once 'Model/RouteXL.php';
require_once 'Model/TripPeriodDNA_XL.php';
require_once 'TimeCalculator.php';

class GuarantyController {

    public function getGuarantyRoutes() {
        $excelRows = $this->readExcelFile();
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

}
