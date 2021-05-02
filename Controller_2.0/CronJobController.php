<?php

require_once 'DAO_2.0/CronJobDao.php';

class CronJobController {

    private $cronJobDao;

    function __construct() {
        $this->cronJobDao = new CronJobDao();
    }

    public function getLoadingStatus(): bool {
        return $inLoadingMode = $this->cronJobDao->isLoading();
    }

}
