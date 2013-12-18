<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/users.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  if ($_SERVER["REQUEST_URI"] == "/users/"){
    $page = new userEdit;
    $page->render();
  } else {
    header("Location:http://doorlock.wrixton.net/users/");
  }
} else {
  header("Location:http://doorlock.wrixton.net/");
  exit();
}

?>
