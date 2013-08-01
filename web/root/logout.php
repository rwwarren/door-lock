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
unset($_COOKIE);
//unset($_COOKIE['PHPSESSID']);
//session_unset();
header("Location:http://doorlock.wrixton.net/");
//echo "Hello <br>";
//print_r($_COOKIE);
//echo $_COOKIE['PHPSESSID'];
exit();
?>
