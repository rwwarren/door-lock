<?php
//try {
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once("$root/../inc/dbcon.php");
//require_once("$root/../inc/template.php");
require_once("$root/../inc/home.php");
require_once("$root/../inc/loggedin.php");
require_once("$root/../inc/extraFunctions.php");
//require_once("$root/../inc/template.php");

if (isLoggedIn()){
  $page = new Loggedin;
} else {
  $page = new Home;
}
$page->render();
?>
