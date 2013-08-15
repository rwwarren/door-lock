<?php
session_name('sid');
session_start();
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
  $dbconn->close();
  //setcookie("TestCookie", $session_id, time()+3600);
  //setcookie("sid", session_id(), time()+3600);
  //setcookie("n", $user, time()+3600);
  header("Location:http://doorlock.wrixton.net/");
} else {
  echo "nope";
  echo '<br>No username or password entered';
}

?>
