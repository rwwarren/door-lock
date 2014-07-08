<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/users.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn()){
  if ($_SERVER["REQUEST_URI"] == "/users/"){
    $page = new userEdit;
    $page->render();
  } else {
    header("Location: http://$_SERVER[HTTP_HOST]");
  }
} else {
  header("Location: http://$_SERVER[HTTP_HOST]");
  exit();
}

?>
