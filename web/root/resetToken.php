<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/resetpass.php");
require_once("$root/../inc/extraFunctions.php");

if (checkTokenValid($_GET['resetToken'])){
  $token = $_GET['resetToken'];
  if ($_SERVER["REQUEST_URI"] == "/forgotPassword/" . $token){
    $page = new ResetPage;
    $page->render();
  } else {
    header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
  }
} else {
  header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
  exit();
}

?>
