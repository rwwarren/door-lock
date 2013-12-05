<?php

session_name('sid');
session_start();
unset($_SESSION['userName']);
unset($_SESSION['isAdmin']);
session_regenerate_id(true);
$_SESSION = array();
session_unset();
session_destroy();
setcookie('sid', '', time()-3600);
session_name('sid');
session_start();

//TODO make it so that it updates the MYSQL db and 
//changes so that the session is not valid and or
//expired and session is not valid
//TODO fix the location
//header("Location:http://doorlock.wrixton.net/");

header("Location:/");

exit();
?>
