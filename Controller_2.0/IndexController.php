<?php

require_once 'DAO_2.0/RouteDao.php';
require_once 'Model_2.0/RouteDate.php';

class IndexController {

    private $routeDao;

    function __construct() {
        $this->routeDao = new RouteDao();
    }

    public function getLastUploadedRoutesDates(): array {
        $queryResultData = $this->routeDao->getLastUploadedRoutesDates();
        $arrayOfRoutes = array();
        foreach ($queryResultData as $row) {
            $routeNumber = $row["number"];
            $dateStamp = $row["date_stamp"];
            if (array_key_exists($routeNumber, $arrayOfRoutes)) {
                $routeDate = $arrayOfRoutes[$routeNumber];
                $dates = $routeDate->getDates();
                array_push($dates, $dateStamp);
                $routeDate->setDates($dates);
            } else {
                $dates = array($dateStamp);
                $routeDate = new RouteDate();
                $routeDate->setRouteNumber($routeNumber);
                $routeDate->setDates($dates);
                $arrayOfRoutes[$routeNumber] = $routeDate;
            }
        }
        return $arrayOfRoutes;
    }

    public function getAllUploadedRoutesDates(): array {

        $queryResultData = $this->routeDao->getAllUploadedRoutesDates();
        $arrayOfRoutes = array();
        foreach ($queryResultData as $row) {
            $routeNumber = $row["route_number"];
            $dateStamp = $row["date_stamp"];
            if (array_key_exists($routeNumber, $arrayOfRoutes)) {
                $routeDate = $arrayOfRoutes[$routeNumber];
                $dates = $routeDate->getDates();
                array_push($dates, $dateStamp);
                $routeDate->setDates($dates);
            } else {
                $dates = array($dateStamp);
                $routeDate = new RouteDate();
                $routeDate->setRouteNumber($routeNumber);
                $routeDate->setDates($dates);
                $arrayOfRoutes[$routeNumber] = $routeDate;
            }
        }
        return $arrayOfRoutes;
    }

}
