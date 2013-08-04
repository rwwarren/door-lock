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
if(isset($_POST['Username']) && isset($_POST['Password'])){
  $user = $_POST['Username'];
  $pass = $_POST['Password'];
  //echo $_POST['Username'];
  //echo "<br>";
  //echo $_POST['Password'];
  //$_POST['Username'] = session_id();
  //$session_id = session_id();
  //session_start();
  //setcookie("TestCookie", $session_id, time()+3600,'/');

  $user = mysql_real_escape_string($user);
  $pass = mysql_real_escape_string($pass);
  $dbconn = new dbconn;
  //$dbconn->close();
  $dbconn->connect("read");
  //$user = $_POST['Username'];//mysql_real_escape_string($_POST['Username']);
  //echo 'user: ' . $user;
  //echo '<br>pass: ' . $pass;
  //$pass = $_POST['Password'];//mysql_real_escape_string($_POST['Password']);
  $dbconn->login($user, $pass);
  //echo $dbconn;
  $dbconn->close();
  //setcookie("TestCookie", $session_id, time()+3600);
  //setcookie("sid", session_id(), time()+3600);
  //setcookie("n", $user, time()+3600);
  header("Location:http://doorlock.wrixton.net/");
} else {
  echo "nope";
  echo '<br>No username or password entered';
}
//global $_COOKIE;
//TODO strip slahes
//TODO other security
//echo "<br> test";
//echo "<br>" . $_COOKIE['PHPSESSID'] . "<br>" ;
//print_r($_COOKIE);
//header("Location:http://doorlock.wrixton.net/");
?>
