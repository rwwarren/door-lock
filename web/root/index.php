<?php
//try {
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
//require_once("$root/../inc/template.php");
require_once("$root/../inc/home.php");
require_once("$root/../inc/loggedin.php");
//require_once("$root/../inc/template.php");

/*
echo '<br><br>';
echo 'This will allow access to the door lock one day';
echo '<br>PiDuinoLock';
echo '<br>';
 */

//$test = new users("write");
//echo '<br>';
/*
public function isLoggedin(){
  return true;
}
*/


$woo = false;

//session_start();
if (session_id() !== "") {
  $woo = true;
}

//doesnt work
if ($woo){
  $page = new Loggedin;
  //works below
} else if (isset($_COOKIE['PHPSESSID'])){
  //echo $_COOKIE['PHPSESSID'];
  $page = new Loggedin; 
} else {
  $page = new Home;
}
$page->render();
//print_r($_COOKIE);
//} catch (Exception $e) {
//  echo 'Exception: ' . $e;
//}
?>
