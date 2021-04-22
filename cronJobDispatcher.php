<?php echo "cron job here"; 



if (file_exists("uploads/cronJobFile.txt")) {

        $myfile = fopen("uploads/cronJobFile.txt", 'r+');
        flock($myfile, LOCK_EX);
        $timeId = stream_get_contents($myfile);

        //  echo $clientId;
        $timeId++;
      
        rewind($myfile);
        ftruncate($myfile, 0);
        fwrite($myfile, $timeId);
        flock($myfile, LOCK_UN);
        fclose($myfile);
    } else {
        $myfile = fopen("uploads/cronJobFile.txt", "w");
        fwrite($myfile, "0");
        $cookie_value = 0;
    }


?>