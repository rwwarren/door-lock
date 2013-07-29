<?php

//session_start();
//$_SESSION = array();
//session_destroy();


//unset($_SESSION );
session_unset(); 
session_write_close(); 
//session_unset();
header("Location:http://doorlock.wrixton.net/");
exit();
?>
