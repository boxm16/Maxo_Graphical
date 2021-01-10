<?php

$mainPageActive = "";
$uploadPageActive = "";
$routeDetailsPageActive = "";
$intervalsActive = "";
$concurrentPageActive = "";

$activeClass = "class=\"active\"";
$currentPage = basename($_SERVER["SCRIPT_FILENAME"]);


switch ($currentPage) {
    case "uploadForm.php":
        $uploadPageActive = $activeClass;
        break;
    case "routeDetails.php":
        $routeDetailsPageActive = $activeClass;
        break;
    case "intervals.ophp":
        $intervalsActive = $activeClass;
        break;
}



echo "<ul>
  <li><a $mainPageActive href=\"index.php\">მთავარ გვერძე დაბრნუნება</a></li>
  <li><a $uploadPageActive href=\"uploadForm.php\">ახალი ფაილის ატვირთვა</a></li>
  <li><a $routeDetailsPageActive href=\"routeDetails.php\">ბრუნები</a></li>
  <li><a $intervalsActive href=\"#about\">ინტერვალები</a></li>
  <li><a $concurrentPageActive href=\"#about\">ერთდროულად მდგომი ავტობუსები</a></li>
</ul>";
   echo "-<br><br>";

