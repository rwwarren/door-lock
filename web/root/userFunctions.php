<?php

session_name('sid');
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require '../includedPackages/authy-php/Authy.php';
require_once("$root/../inc/variables.php");

//if(in_array($functName,$validFunctions))
//if(in_array($_REQUEST['test'], array('test'))){
if (isset($_GET['actions'])){
  $type = $_GET['actions'];
  if($type == 'test') {
    registerUser();
    //isset($_POST['personName']) && isset($_POST['name']) && isset($_POST['password']) && isset($_POST['email']
  } else if ($type == 'login'){
    login();
  } else if ($type == 'logout'){
    logout();
  } else if ($type == 'registerUser'){
    registerUser();
  } else if ($type == 'changeUser'){
    changeUser();
  } else {
    echo "You don't have permission to call that function so back off!";
    exit();
  }
} else {
  echo "improper request";
  header("Location:/");
  exit();
}

//add_action( 'wp_ajax_example_ajax_request', 'example_ajax_request' );

//TODO have this is extra functions
function checkHeaders(){
  if (isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/"){
    return true;
  } else {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    //include("$root/../inc/variables.php");
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

function login(){
  
  //TODO add check headers and other functions
  if(isset($_POST['Username']) && isset($_POST['Password']) /*&& checkHeaders()*/ && isset($_POST['Token'])){
                          //($requiredHeaders == $sentHeaders || isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/")){
    echo "This is a test";
    //print_r($_SERVER['HTTP_REFERER']);
    //if ($_SERVER['HTTP_REFERER'] == "http://doorlock.wrixton.net/"){
    //  echo "they equal";
    //}
    $user = $_POST['Username'];
    $pass = $_POST['Password'];
    $token = $_POST['Token'];
  
    $user = mysql_real_escape_string($user);
    $pass = mysql_real_escape_string($pass);
    $dbconn = new dbconn;
    //$dbconn->close();
    //TODO this is the sandbox one
    //TODO also remove my secret key
    //TODO add this to the database
    //$authy_id = get from DB;
    //TODO add this back in to the check
    //$verification = $authy_api->verifyToken("$authy_id", "$token");
    //if($verification->ok()){
    //TODO above commented out to save testing hassle
    $test = true;
    if($test == true){
      echo '<br> authy token is okay';
      $dbconn->connect("read");
      //$user = $_POST['Username'];//mysql_real_escape_string($_POST['Username']);
      //echo 'user: ' . $user;
      //echo '<br>pass: ' . $pass;
      //$pass = $_POST['Password'];//mysql_real_escape_string($_POST['Password']);
      $dbconn->login($user, $pass);
      $dbconn->close();
    } else { //authy is not right
      header("HTTP/1.0 401 Authy key wrong");
    }
    //setcookie("TestCookie", $session_id, time()+3600);
    //setcookie("sid", session_id(), time()+3600);
    //setcookie("n", $user, time()+3600);
    //TODO get the location working again
    //header("Location:http://doorlock.wrixton.net/");
    //header("Location:/");
  } else {
    echo "nope";
    echo '<br>No username or password entered';
    header("HTTP/1.0 400 Username or password not entered");
    //exit();
  }
}

function logout(){
  unset($_SESSION['userName']);
  unset($_SESSION['isAdmin']);
  session_regenerate_id(true);
  $_SESSION = array();
  session_unset();
  session_destroy();
  setcookie('sid', '', time()-3600);
  session_name('sid');
  session_start();
  
  //TODO make it so that it updates the MYSQL db and 
  //changes so that the session is not valid and or
  //expired and session is not valid
  //TODO fix the location
  header("Location:/");
  exit();
}

function changeUser(){

}

function registerUser(){
  if (isset($_POST['personName']) && isset($_POST['name']) && isset($_POST['password']) && isset($_POST['email']) /*&& isAdmin()*/){
    //asdf
    echo 'this is done';
  } else {
    echo 'nothing returned';
  }
}

//TODO maybe forgotPassword?

?>
