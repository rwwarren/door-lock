<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/lock.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  if ($_SERVER["REQUEST_URI"] == "/lock/"){
    $page = new lock;
    $page->render();
  } else {
    //header("Location:https://doorlock.wrixton.net/lock/");
    header("Location: http://$_SERVER[HTTP_HOST]");
  }
} else {
  //header("Location:https://doorlock.wrixton.net/");
  header("Location: http://$_SERVER[HTTP_HOST]");
  exit();
}

?>
