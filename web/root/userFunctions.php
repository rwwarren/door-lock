<?php

session_name('sid');
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require '../includedPackages/authy-php/Authy.php';
require_once("$root/../inc/variables.php");
require_once("$root/../inc/extraFunctions.php");

if (isset($_GET['actions']) ){
  $type = $_GET['actions'];
  if ($type == 'login'){
    login();
  } else if ($type == 'logout' && isLoggedIn()){
    logout();
  } else if ($type == 'registerUser' && isLoggedIn()){
    registerUser();
  } else if ($type == 'changeUser' && isLoggedIn()){
    changeUser();
  } else if ($type == 'changePassword' && isLoggedIn()){
    changePassword();
  } else if ($type == 'forgotPassword'){
    forgotPassword();
  } else {
    echo "You don't have permission to call that function so back off!";
    header("HTTP/1.0 403 User Forbidden");
    exit();
  }
} else {
  echo "improper request";
  header("Location:/");
  exit();
}

//TODO have this is extra functions
function checkHeaders(){
  if (isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/"){
    return true;
  } else {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
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
    //$dbconn->connect("read");
    //$authyValid = $dbconn->checkAuthy($user, $token);
    //$dbconn->close();
    //TODO add this back in to the check
    //$verification = $authy_api->verifyToken("$authy_id", "$token");
    //if($authyValid && $verification->ok()){
    //TODO above commented out to save testing hassle
    $test = true;
    if($test == true){
      echo '<br> authy token is okay';
      $dbconn->connect("read");
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
  header("Location:/");
  exit();
}

function changeUser(){

  if(isset($_POST['user']) && isset($_POST['type']) && isAdmin() /*&& checkHeaders()*/){
                          //($requiredHeaders == $sentHeaders || isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/")){
    echo "This is a test";
    $user = $_POST['user'];
    $type = $_POST['type'];
  
    $user = mysql_real_escape_string($user);
    $dbconn = new dbconn;
    $dbconn->connect("write");
    $dbconn->changeUser($user, $type);
    $dbconn->close();
  } else {
    echo "nope";
    echo '<br>No username entered';
    header("HTTP/1.0 403 User Forbidden");
  }
}

function registerUser(){
  if (isset($_POST['personName']) && isset($_POST['username'])&& isset($_POST['password']) && isset($_POST['email']) && isAdmin() && isset($_POST['admin'])){
    $personName = $_POST['personName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $admin = ($_POST['admin'] == 'true' ? 1 : 0);

    $personName = mysql_real_escape_string($personName);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    $dbconn = new dbconn;
    $dbconn->connect("write");
    $dbconn->registerUser($personName, $username, $password, $email, $admin);
    $dbconn->close();
  } else {
    echo 'nothing returned';
    header("HTTP/1.0 403 User Forbidden");
  }
}

function changePassword(){
  if (isset($_SESSION['username']) && isset($_POST['oldPassword']) && isset($_POST['newPassword']) ){
    $username = $_SESSION['username'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    $username = mysql_real_escape_string($username);
    $oldPassword = mysql_real_escape_string($oldPassword);
    $newPassword = mysql_real_escape_string($newPassword);
    $dbconn = new dbconn;
    $dbconn->connect("write");
    $result = $dbconn->changePassword($username, $oldPassword, $newPassword);
    $dbconn->close();

    if ($result == 200){
      logout();
    }
    //header("HTTP/1.0 200 Success, Password Changed");
  } else {
    print_r($_POST);
    echo 'nothing returned';
    header("HTTP/1.0 401 User Forbidden");
  }
}

function forgotPassword(){
  if (isset($_POST['username']) && isset($_POST['email'])){
    //asdf
    /*
    echo 'this is done';
    print_r($_POST);
    $personName = $_POST['personName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $admin = ($_POST['admin'] == 'true' ? 1 : 0);

    $personName = mysql_real_escape_string($personName);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    $dbconn = new dbconn;
    $dbconn->connect("write");
    $dbconn->resetPassword($username, $email);
    $dbconn->close();
     */
  } else {
    echo 'nothing returned';
    header("HTTP/1.0 403 User Forbidden");
  }
}

?>
