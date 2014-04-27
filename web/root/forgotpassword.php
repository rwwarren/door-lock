<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/forgotpage.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  header("Location:https://doorlock.wrixton.net/");
} else {
  $page = new ForgotPage;
}
$page->render();

?>
