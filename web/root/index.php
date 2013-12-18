<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/home.php");
require_once("$root/../inc/loggedin.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  $page = new Loggedin;
} else {
  $page = new Home;
}
$page->render();
?>
