<?php

$mainPageActive = "";
$uploadPageActive = "";
$routeDetailsPageActive = "";
$intervalsActive = "";
$excelFormActive = "";
$concurrentPageActive = "";
$filterButton = "";
$markerButton = "";
$tableCopyButton = "";
$excelExportButton = "";
$b = "";
$activeClass = "class=\"active\"";
$currentPage = basename($_SERVER["SCRIPT_FILENAME"]);


switch ($currentPage) {
    case "uploadForm.php":
        $uploadPageActive = $activeClass;
        break;
    case "routeDetails.php":
        $routeDetailsPageActive = $activeClass;
        $filterButton = "<li style=\"padding-left:50px\"><button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#filterModal\">ფილტრები</button></li>";
        $markerButton = "<li style=\"padding-left:50px\"><button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#markerModal\">მარკერები</button></li>";
        $tableCopyButton = "<li style=\"padding-left:50px\"><button  class=\"btn btn-warning\" onclick=\"copytable('mainTable')\">ცხრილის კოპირება</button></li>";

        break;
    case "intervals.php":
        $intervalsActive = $activeClass;
        $excelExportButton = " <li style=\"padding-left:50px\">
            <form id=\"convert_form\" action=\"intervalsExcelExport.php\" method=\"POST\">
            <button type=\"submit\"class=\"btn btn-warning \">ექსელში ექსპორტი</button>
          </form></li>";
        break;
    case "excelForm.php":
        $excelFormActive = $activeClass;
        $filterButton = "<li style=\"padding-left:50px\"><button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#filterModal\">ფილტრები</button></li>";
        $markerButton = "<li style=\"padding-left:50px\"><button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#calculationModal\">გამოთვლები</button></li>";
        $excelExportButton = " <li style=\"padding-left:50px\">
            <form id=\"convert_form\" action=\"excelExport.php\" method=\"POST\">
            <button type=\"submit\"class=\"btn btn-warning \">ექსელში ექსპორტი</button>
          </form></li>";
        $b = " <li><div class=\"dropdown\">
            <button class=\"dropbtn\">Dropdown</button>
            <div class=\"dropdown-content\">
                <a href=\"#\">Link 1</a>
                <a href=\"#\">Link 2</a>
                <a href=\"#\">Link 3</a>
            </div>
        </div></li>";
        break;
}



echo "<ul>
  <li><a $mainPageActive href=\"index.php\">საწყისი გვერდი</a></li>
  <li><a $uploadPageActive href=\"uploadForm.php\">ახალი ფაილის ატვირთვა</a></li>
  <li><a $routeDetailsPageActive href=\"routeDetails.php\">ბრუნები</a></li>
  <li><a $intervalsActive href=\"intervals.php\">ინტერვალები</a></li>
  <li><a $excelFormActive href=\"excelForm.php\">ექსელის ფორმა</a></li>
   $b   
  $filterButton
  $markerButton
  $tableCopyButton
  $excelExportButton
      
</ul>";
echo "-<br><br>";

