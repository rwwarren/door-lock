<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/forgotpage.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  header("Location: http://$_SERVER[HTTP_HOST]");
} else {
  $page = new ForgotPage;
}
$page->render();

?>
