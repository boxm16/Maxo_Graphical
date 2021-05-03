<?php

class ReportController {

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

}
