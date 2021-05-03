<?php

require_once 'DAO_2.0/RouteDao.php';
require_once 'Model_2.0/RouteDate.php';

class IndexController {

    private $routeDao;

    function __construct() {
        $this->routeDao = new RouteDao();
    }

    public function getLastUploadedRoutesDates(): array {
        return $this->routeDao->getLastUploadedRoutesDates();
       
    }

    public function getAllUploadedRoutesDates(): array {

        return  $this->routeDao->getAllUploadedRoutesDates();
        
    }

}
