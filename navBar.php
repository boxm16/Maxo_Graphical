<?php

$mainPageActive = "";
$uploadPageActive = "";
$routeDetailsPageActive = "";
$intervalsActive = "";
$concurrentPageActive = "";
$filterButton = "";
$activeClass = "class=\"active\"";
$currentPage = basename($_SERVER["SCRIPT_FILENAME"]);


switch ($currentPage) {
    case "uploadForm.php":
        $uploadPageActive = $activeClass;
        break;
    case "routeDetails.php":
        $routeDetailsPageActive = $activeClass;
        $filterButton = "<li style=\"padding-left:100px\"><button style=\"background-color:blue\">ფილტრები</button></li>";
        break;
    case "intervals.php":
        $intervalsActive = $activeClass;
        break;
}



echo "<ul>
  <li><a $mainPageActive href=\"index.php\">მთავარ გვერძე დაბრნუნება</a></li>
  <li><a $uploadPageActive href=\"uploadForm.php\">ახალი ფაილის ატვირთვა</a></li>
  <li><a $routeDetailsPageActive href=\"routeDetails.php\">ბრუნები</a></li>
  <li><a $intervalsActive href=\"intervals.php\">ინტერვალები</a></li>
  $filterButton
</ul>";
echo "-<br><br>";

