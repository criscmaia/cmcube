<?php
$IPaddress = $_SERVER['REMOTE_ADDR'];
$referer   = $_SERVER['HTTP_REFERER'];
$browser   = $_SERVER['HTTP_USER_AGENT'];
$time      = date("H:i dS F");
$httpHost  = $_SERVER['HTTP_HOST'];

$myfile = fopen("visitors.txt", "a+") or die("Unable to open file!");           // to write and keep previous data:

$txt = "\r\n" .
       ".\r\n Time: " . $time .   
       ".\r\n IP: " . $IPaddress . 
       ".\r\n Referer: " . $referer . 
       ".\r\n Browswer: " . $browser . 
       ".\r\n HTTP Host: " . $httpHost; 
fwrite($myfile, $txt);
fclose($myfile);

$myfile = fopen("visitors.txt", "r") or die ("Unable to open file!");           // read and print with break lines
$fileContent = fread($myfile,filesize("visitors.txt"));
echo nl2br($fileContent);
fclose($myfile);

?>