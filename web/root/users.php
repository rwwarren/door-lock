<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/users.php");

//TODO validate with mysql?
//or something secure
//maybe memchache? or like redis?
if (isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['sid']) && isset($_COOKIE['n'])){
  $page = new userEdit;
  $page->render();
} else {
  header("Location:http://doorlock.wrixton.net/");
  exit();
}

?>
