<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/home.php");
require_once("$root/../inc/loggedin.php");
require_once("$root/../inc/extraFunctions.php");

if(isMobile()){
  header("Location: http://m.$_SERVER[HTTP_HOST]");
  //print_r($_SERVER);
  exit();
}

if ($_SERVER["REQUEST_URI"] == "/"){
  if (isLoggedIn()){
    $page = new Loggedin;
  } else {
    $page = new Home;
  }
  $page->render();
} else {
  header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
  exit();
}
?>
