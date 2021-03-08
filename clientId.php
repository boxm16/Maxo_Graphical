<?php

if (isset($_COOKIE["clientId"])) {
    // echo "Client id=" . $_COOKIE["clientId"];
    $clientId = $_COOKIE["clientId"];
} else {
    //   echo "<h1>Request from a new client has been detected</h1><br>";
    if (file_exists("uploads/clientIdCounter.txt")) {

        $myfile = fopen("uploads/clientIdCounter.txt", 'r+');
        flock($myfile, LOCK_EX);
        $clientId = stream_get_contents($myfile);

        //  echo $clientId;
        $clientId++;
        $cookie_value = $clientId;
        rewind($myfile);
        ftruncate($myfile, 0);
        fwrite($myfile, $clientId);
        flock($myfile, LOCK_UN);
        fclose($myfile);
    } else {
        $myfile = fopen("uploads/clientIdCounter.txt", "w");
        fwrite($myfile, "0");
        $cookie_value = $clientId = 0;
    }
    $cookie_name = "clientId";

    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day 
    //  echo "<h1>Client Id has been set to $clientId for 30 days</h1>";
}








