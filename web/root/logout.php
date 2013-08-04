<?php

//session_start();
//$_SESSION = array();
//session_destroy();


//unset($_SESSION );
/*
session_unset(); 
session_destroy();
session_write_close(); 
setcookie('TestCookie','',time()-3600);
 */
setcookie('PHPSESSID', '', time()-3600);
//unset($_COOKIE);
setcookie('sid', '', time()-3600);
setcookie('n', '', time()-3600);
//unset($_COOKIE['sid']);
//session_unset();
//TODO make it so that it updates the MYSQL db and 
//changes so that the session is not valid and or
//expired and session is not valid
header("Location:http://doorlock.wrixton.net/");
//echo "Hello <br>";
//print_r($_COOKIE);
//echo $_COOKIE['PHPSESSID'];
exit();
?>
