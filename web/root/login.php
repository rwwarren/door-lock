<?php
session_name('sid');
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
//require_once("$root/../inc/variables.php");
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
function checkHeaders(){
  if (isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/"){
    return true;
  } else {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include("$root/../inc/variables.php");
    $sentHeaders = getallheaders();
    unset($sentHeaders['User-Agent']);
    unset($sentHeaders['Host']);
    unset($sentHeaders['Accept']);
    unset($sentHeaders['Content-Length']);
    unset($sentHeaders['Content-Type']);
    if ($requiredHeaders == $sentHeaders){
      return true;
    } else {
      return false;
    }
  }
}
//$sentHeaders = getallheaders();
//unset($sentHeaders['User-Agent']);
//unset($sentHeaders['Host']);
//unset($sentHeaders['Accept']);
//unset($sentHeaders['Content-Length']);
//unset($sentHeaders['Content-Type']);
//print_r($sentHeaders);

if(isset($_POST['Username']) && isset($_POST['Password']) && checkHeaders()){
                        //($requiredHeaders == $sentHeaders || isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/")){
  echo "This is a test";
  //print_r($_SERVER['HTTP_REFERER']);
  //if ($_SERVER['HTTP_REFERER'] == "http://doorlock.wrixton.net/"){
  //  echo "they equal";
  //}
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
  //TODO get the location working again
  //header("Location:http://doorlock.wrixton.net/");
  header("Location:/");
} else {
  echo "nope";
  echo '<br>No username or password entered';
  header("HTTP/1.0 400 Username or password not entered");
  //exit();
}

?>
