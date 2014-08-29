<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/lock.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  if ($_SERVER["REQUEST_URI"] == "/lock/"){
    $page = new lock;
    $page->render();
  } else {
    header("Location: http://$_SERVER[SERVER_NAME]");
  }
} else {
  header("Location: http://$_SERVER[SERVER_NAME]");
  exit();
}

?>
