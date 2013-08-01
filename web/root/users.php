<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/users.php");

if (isset($_COOKIE['PHPSESSID'])){
  $page = new userEdit;
  $page->render();
} else {
  header("Location:http://doorlock.wrixton.net/");
  exit();
}

?>
