<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://docs.doorlock.apiary.io/");

curl_exec($ch);
//echo $ch;
curl_close($ch);

?>
