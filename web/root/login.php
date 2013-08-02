<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
  //public function login() {
    //login to site
    //$db = new dbconn;
    //start session and stayed logged in
    /*
    if (array_key_exists('Username', $_POST) && array_key_exists('Password', $_POST)) {
      $name = $_POST['Username'];
      $password = $_POST['Password'];
      $user = $db->login($name, $password);
      if($username == 'demo' && $password == 'demo'){
        echo "success";
      }
      //here
      //some login
      //jQuery / js stuff
    }*/
  //}
//TODO remove this
if(isset($_POST)){
  echo $_POST['Username'];
  echo "<br>";
  echo $_POST['Password'];
} else {
  echo "nope";
}
//global $_COOKIE;
//TODO strip slahes
//TODO other security
$_POST['Username'] = session_id();
$session_id = session_id();
session_start();
//setcookie("TestCookie", $session_id, time()+3600,'/');

$dbconn = new dbconn;
//$dbconn->close();
$dbconn->connect("read");
//echo $dbconn;



setcookie("TestCookie", $session_id, time()+3600);
//echo "<br> test";
//echo "<br>" . $_COOKIE['PHPSESSID'] . "<br>" ;
//print_r($_COOKIE);
//header("Location:http://doorlock.wrixton.net/");
?>
