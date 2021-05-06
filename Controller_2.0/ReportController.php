<?php

require_once 'DAO_2.0/ReportDao.php';

class ReportController {

    private $reportDao;

    function __construct() {
        $this->reportDao = new ReportDao();
    }

    public function getReportList(): array {
        $dir = "reports";
        $list = scandir($dir); //in ascending order
        // $list= scandir($dir,1);//in descending order
        if ($list == null) {
            return array();
        } else {
            return $list;
        }
    }

    public function registerReports($requestedReportsData) {
        $requestedRoutesDates = $requestedReportsData["routes:dates"];
        if (strlen($requestedRoutesDates) == 0) {
            echo "No routes and dates has been selected<br>";
        } else {
            if (isset($requestedReportsData["routeDetailsReport"]) && $requestedReportsData["routeDetailsReport"] == "on") {
                $lastInsertdionId = $this->reportDao->registerRouteDetailsReport(); //this function inserts data to report_tech and return id numbero of this(last)insertion
                $nsertionData = $this->convertRequestedRoutesAndDatesToInsertionData($lastInsertdionId, $requestedRoutesDates);
                $this->reportDao->registerReportData($nsertionData);
            }
            if (isset($requestedReportsData["intervalsReport"]) && $requestedReportsData["intervalsReport"] == "on") {
                //registerRouteDetailsReport
            }
            if (isset($requestedReportsData["excelFormReport"]) && $requestedReportsData["excelFormReport"] == "on") {
                //registerRouteDetailsReport
            }
        }
    }

    private function convertRequestedRoutesAndDatesToInsertionData($reportId, $requestedRoutesAndDates) {
        $insertionData = array();
        $routesAndDates = explode(",", $requestedRoutesAndDates);
        foreach ($routesAndDates as $routeAndDate) {
            if ($routeAndDate != "") {
                $d = explode(":", $routeAndDate);
                $routNumber = $d[0];
                $date = $d[1];
                $insertionRow = array($reportId, $routNumber, $date);
                array_push($insertionData, $insertionRow);
            }
        }
        return $insertionData;
    }

}
