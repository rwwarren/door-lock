<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/admin.php");
require_once("$root/../inc/config.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn() && isAdmin()){
  if ($_SERVER["REQUEST_URI"] == "/config/"){
    $page = new ConfigPage;
    $page->render();
  } else {
    header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
  }
} else {
  header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
  exit();
}

?>
