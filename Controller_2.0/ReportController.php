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
        $routesDates = $requestedReportsData["routes:dates"];
        if (strlen($routesDates) == 0) {
            echo "No routes and dates has been selected<br>";
        } else {
            if (isset($requestedReportsData["routeDetailsReport"]) && $requestedReportsData["routeDetailsReport"] == "on") {
                $this->reportDao->registerRouteDetailsReport();
                // $reportId=$this->reportDao->getLastInertedReportId();
                //  $this->reportDao->registerReportData($reportId, $requestedReportsData);
            }
            if (isset($requestedReportsData["intervalsReport"]) && $requestedReportsData["intervalsReport"] == "on") {
                //registerRouteDetailsReport
            }
            if (isset($requestedReportsData["excelFormReport"]) && $requestedReportsData["excelFormReport"] == "on") {
                //registerRouteDetailsReport
            }
        }
    }

}
