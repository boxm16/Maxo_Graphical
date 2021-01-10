<?php

$mainPageActive = "";
$uploadPageActive = "";
$tripsPageActive = "";
$intervalsActive = "";
$concurrentPageActive = "";

$activeClass = "class=\"active\"";
$currentPage = basename($_SERVER["SCRIPT_FILENAME"]);


switch ($currentPage) {
    case "uploadForm.php":
        $uploadPageActive = $activeClass;
        break;
    case "trips.php":
        $tripsPageActive = $activeClass;
        break;
    case "intervals.ophp":
        $intervalsActive = $activeClass;
        break;
    
}



echo "<ul>
  <li><a $mainPageActive href=\"#home\">მთავარ გვერძე დაბრნუნება</a></li>
  <li><a $uploadPageActive href=\"#news\">ახალი ფაილის ატვირთვა</a></li>
  <li><a $tripsPageActive href=\"#contact\">ბრუნები</a></li>
  <li><a $intervalsActive href=\"#about\">ინტერვალები</a></li>
  <li><a $concurrentPageActive href=\"#about\">ერთდროულად მდგომი ავტობუსები</a></li>
</ul>";
echo "<hr><hr><hr>";

