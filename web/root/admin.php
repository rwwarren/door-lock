<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/admin.php");
require_once("$root/../inc/extraFunctions.php");

if (isLoggedIn() && isAdmin()){
  if ($_SERVER["REQUEST_URI"] == "/admin/"){
    $page = new adminPage;
    $page->render();
  } else {
    //header("Location:https://doorlock.wrixton.net/admin/");
    header("Location: http://$_SERVER[HTTP_HOST]");
  }
} else {
  //header("Location:https://doorlock.wrixton.net/");
  header("Location: http://$_SERVER[HTTP_HOST]");
  exit();
}

?>
