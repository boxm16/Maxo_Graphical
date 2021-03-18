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
$dropDown = "";
$activeClass = "class=\"active\"";
$currentPage = basename($_SERVER["SCRIPT_FILENAME"]);


switch ($currentPage) {
    case "db_uploadForm.php":
        $uploadPageActive = $activeClass;
        break;
    case "db_routeDetails.php":
        $routeDetailsPageActive = $activeClass;

        $dropDown = " <li style=\"padding-left:150px\"><div class=\"dropdown\">
            <button class=\"dropbtn\">ფუნქციები</button>
            <div class=\"dropdown-content\">
                <button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#filterModal\">ფილტრები</button><br>
                <button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#markerModal\">მარკერები</button><br>
              <button  class=\"btn btn-warning\" onclick=\"copytable('mainTable')\">ცხრილის კოპირება</button>
           </div></li>";



        break;
    case "db_intervals.php":
        $intervalsActive = $activeClass;
        $excelExportButton = " <li style=\"padding-left:50px\">
            <form id=\"convert_form\" action=\"intervalsExcelExport.php\" method=\"POST\">
            <button type=\"submit\"class=\"btn btn-warning \">ექსელში ექსპორტი</button>
          </form></li>";
        break;
    case "excelForm.php":
        $excelFormActive = $activeClass;

        $dropDown = " <li style=\"padding-left:150px\"><div class=\"dropdown\">
            <button class=\"dropbtn\">ფუნქციები</button>
            <div class=\"dropdown-content\">
                <button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#filterModal\">ფილტრები</button><br>
                <button  class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#calculationModal\">გამოთვლები</button>
               <form id=\"convert_form\" action=\"excelExport.php\" method=\"POST\">
            <button type=\"submit\"class=\"btn btn-warning \">ექსელში ექსპორტი</button>
          </form>
         </div>
       </li>";
        break;
}



echo "<ul>
  <li><a $mainPageActive href=\"db_index.php\">საწყისი გვერდი</a></li>
  <li><a $uploadPageActive href=\"db_uploadForm.php\">ახალი ფაილის ატვირთვა</a></li>
  <li><a $routeDetailsPageActive href=\"db_routeDetails.php\">ბრუნები</a></li>
  <li><a $intervalsActive href=\"db_intervals.php\">ინტერვალები</a></li>
  <li><a $excelFormActive href=\"db_excelForm.php\">ექსელის ფორმა</a></li>
  $dropDown 
  $filterButton
  $markerButton
  $tableCopyButton
  $excelExportButton
      
</ul>";
echo "-<br><br>";

