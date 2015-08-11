<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once("$root/../inc/config.php");
//require_once("$root/../inc/extraFunctions.php");

//TODO remove this

//if (isLoggedIn() && isAdmin() && $_SERVER["REQUEST_URI"] == "/config/"){
if ( $_SERVER["REQUEST_URI"] == "/config/"){
  $page = new ConfigPage;
  $page->render();
} else {
  header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
  exit();
}

?>
