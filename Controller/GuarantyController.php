<?php

require_once "SimpleXLSX.php";
require_once 'Model/RouteGuaranteed.php';
require_once 'TimeCalculator.php';

class GuarantyController {

    public function getGuarantyRoutes() {
        $excelRows = $this->readExcelFile();
        $guarantyRoutes = $this->getGuarantyRoutesFromExcelRows($excelRows); //array of routes
       // echo 'Peak usage:(' . ( (memory_get_peak_usage() / 1024 ) / 1024) . 'M) <br>';
       // exit;
        return $guarantyRoutes;
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

    private function getGuarantyRoutesFromExcelRows($excelRows) {
        $routes = array();
        foreach ($excelRows as $row) {
            // sifting off empty rows and rows that show something else
            if ($row[7]["value"] == "მარშრუტი" || $row[7]["value"] == "კონდუქტორი" || $row[7]["value"] == "") {

                continue;
            }

            //here i put security mechnism, if the file is not the file for guarnatees

            $startTimeActual = $row[13]["value"];
            if ($startTimeActual != "") {
                echo "ატვირთული ფაილი არ არის საგარანტიო გასცლების გამოსათვლელად გამოსადეგი (ფაილში იძებნება ფაქტიური გასვლის დრო, რაც აქ დაუშვებელია)";
                echo "<a href='index.php'>დაბრუნდი უკან  და ატვირთე შესაბამისი ფაილი</a>";
                exit;
            }

            $routeNumber = $row[7]["value"];
            if (array_key_exists($routeNumber, $routes)) {
                $existingRoute = $routes[$routeNumber];
                $refilledRoute = $this->addRowElementsToRoute($existingRoute, $row);
                $routes[$routeNumber] = $refilledRoute;
            } else {
                $baseNumber = $row[0]["value"];
                $busType = $row[1]["value"];
                $newRoute = new RouteGuaranteed();
                $newRoute->setNumber($routeNumber);
                $newRoute->setBaseNumber($baseNumber);
                $newRoute->setBusType($busType);
                $refilledRoute = $this->addRowElementsToRoute($newRoute, $row);
                $routes[$routeNumber] = $refilledRoute;
            }
        }
        // $routes = $this->setTripPeriodDNAs($routes);

        return $routes;
    }

    private function addRowElementsToRoute($route, $row) {
        $dateStamp_1 = $row[5]["value"];
        $dateStamp_2 = $route->getDateStamp();

        if ($dateStamp_2 == null) {
            $route->setDateStamp($dateStamp_1);
        } else {
            if ($dateStamp_1 == $dateStamp_2) {
                $route = $this->addElementsToExistingDay($route, $row);
            } else {
                echo "ატვირთული ფაილი არ არის საგარანტიო გასცლების გამოსათვლელად გამოსადეგი (ფაილში იძებნება სხვადასხვა რიცხვი, რაც აქ დაუშვებელია)";
                echo "<a href='index.php'>დაბრუნდი უკან  და ატვირთე შესაბამისი ფაილი</a>";
                exit;
            }
        }

        return $route;
    }

    private function addElementsToExistingDay($route, $row) {
        $exodusNumber = $row[8]["value"];
        $exoduses = $route->getExoduses();
        if (array_key_exists($exodusNumber, $exoduses)) {
            $existingExodus = $exoduses[$exodusNumber];
            $refilledExodus = $this->addElementsToExistingExodus($existingExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        } else {
            $newExodus = new ExodusGuaranteed();
            $newExodus->setNumber($exodusNumber);
            $refilledExodus = $this->addElementsToExistingExodus($newExodus, $row);
            $exoduses[$exodusNumber] = $refilledExodus;
        }
        $route->setExoduses($exoduses);
        return $route;
    }

    private function addElementsToExistingExodus($exodus, $row) {

        $tripPeriods = $exodus->getTripPeriods();
        $tripPeriodTypeStampInRowCell = $row[15]["value"];
        $tripPeriodType = $this->getTripTypeFromTripStampInRowCell($tripPeriodTypeStampInRowCell);
        if ($tripPeriodType == "baseLeaving") {
            $tripPeriod = $this->createBaseLeavingPeriod($row);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "baseReturn") {
            $tripPeriod = $this->createBaseReturnPeriod($row);
            //  $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
            array_push($tripPeriods, $tripPeriod);
        }
        if ($tripPeriodType == "break") {
            /* do nothing
             * 
              $tripPeriod = $this->createBreakPeriod($row);
              $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
              array_push($tripPeriods, $tripPeriod);
             *
             */
        }
        if ($tripPeriodType == "round") {
            $tripPeriodsOfRound = $this->createTripPeridsOfRound($row);
            foreach ($tripPeriodsOfRound as $tripPeriod) {
                // $tripPeriod = $this->addPreviosTripPeriodTimes($tripPeriod, $tripPeriods);
                array_push($tripPeriods, $tripPeriod);
            }
        }

        $exodus->setTripPeriods($tripPeriods);
        return $exodus;
    }

    private function createBaseLeavingPeriod($row) {

        if ($row[16]["value"] != "") {
            $tripPeriodType = "baseLeaving_A";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriodType = "baseLeaving_B";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBaseReturnPeriod($row) {

        if ($row[16]["value"] != "") {
            $tripPeriodType = "A_baseReturn";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriodType = "B_baseReturn";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createBreakPeriod($row) {
        $tripPeriodType = "break";
        if ($row[16]["value"] != "") {
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
        } else {
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
        }
        return $tripPeriod;
    }

    private function createTripPeridsOfRound($row) {
        $tripPeriodsOfRound = array();
        if ($row[16]["value"] != "" && $row[22]["value"] != "") {
            $leftSideTime = $this->time24($row[16]["value"]);
            $rightSideTime = $this->time24($row[22]["value"]);
            $timeCalculator = new TimeCalculator();
            $leftSideTimeInSeconds = $timeCalculator->getSecondsFromTimeStamp($leftSideTime);
            $rightSideTimeInSeconds = $timeCalculator->getSecondsFromTimeStamp($rightSideTime);
            if ($leftSideTimeInSeconds < $rightSideTimeInSeconds) {
                $tripPeriodType = "ab";
                $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                $tripPeriodType = "ba";
                $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                return $tripPeriodsOfRound;
            } else {

                $tripPeriodType = "ba";
                $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                $tripPeriodType = "ab";
                $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
                array_push($tripPeriodsOfRound, $tripPeriod);
                return $tripPeriodsOfRound;
            }
        }
        if ($row[16]["value"] != "") {
            $tripPeriodType = "ab";
            $tripPeriod = $this->createTripPeriodFromLeftSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        if ($row[22]["value"] != "") {
            $tripPeriodType = "ba";
            $tripPeriod = $this->createTripPeriodFromRightSide($row, $tripPeriodType);
            array_push($tripPeriodsOfRound, $tripPeriod);
        }
        return $tripPeriodsOfRound;
    }

    private function getTripTypeFromTripStampInRowCell($tripPeriodTypeStampInRowCell) {
        //baseLeaving, baseReturn, ab, ba, break

        if (strpos($tripPeriodTypeStampInRowCell, "გასვლა") != null) {
            return "baseLeaving";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "შესვენება") != null) {
            return "break";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "დაბრუნება") != null) {
            return "baseReturn";
        }
        if (strpos($tripPeriodTypeStampInRowCell, "ბრუნი") != null) {
            return "round";
        }
    }

    private function createTripPeriodFromLeftSide($row, $type) {

        $startTimeScheduled = $this->time24($row[16]["value"]);
        $arrivalTimeScheduled = $this->time24($row[19]["value"]);
        $tripPeriod = new TripPeriodGuaranteed($type, $startTimeScheduled, $arrivalTimeScheduled);
        return $tripPeriod;
    }

    private function createTripPeriodFromRightSide($row, $type) {
        $startTimeScheduled = $this->time24($row[22]["value"]);
        $arrivalTimeScheduled = $this->time24($row[25]["value"]);
        $tripPeriod = new TripPeriodGuaranteed($type, $startTimeScheduled, $arrivalTimeScheduled);
        return $tripPeriod;
    }

    private function addPreviosTripPeriodTimes($tripPeriod, $tripPeriods) {

        $previousTripPeriod = $tripPeriods[count($tripPeriods) - 1];
        $previousTripPeriodArrivalTimeActual = $previousTripPeriod->getArrivalTimeActual();
        $previousTripPeriodArrivalTimeScheduled = $previousTripPeriod->getArrivalTimeScheduled();
        $tripPeriod->setPreviousTripPeriodArrivalTimeActual($previousTripPeriodArrivalTimeActual);
        $tripPeriod->setPreviousTripPeriodArrivalTimeScheduled($previousTripPeriodArrivalTimeScheduled);
        return $tripPeriod;
    }

    private function time24($shortTimeStamp) {
        if ($shortTimeStamp == "") {
            return $shortTimeStamp;
        }
        $splittedTime = explode(":", $shortTimeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        $totalSeconds = ($hours * 60 * 60) + ($minutes * 60);
        if ($totalSeconds > 3 * 60 * 60) {
            return $shortTimeStamp;
        } else {
            $totalSeconds += 24 * 60 * 60;
            $h = floor($totalSeconds / 3600);
            $m = floor(($totalSeconds - ($h * 3600)) / 60);

            return sprintf('%02d', $h) . ":" . sprintf('%02d', $m);
        }
    }

}
