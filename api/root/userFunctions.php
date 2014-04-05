<?php

ini_set("session.hash_function", "sha512");
session_name('sid');

//session_set_cookie_params(0, '/', '.wrixton.net');
//ini_set('session.cookie_domain', '.wrixton.net');

session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//echo $root . "/../../web/inc/dbcon.php";
require_once("$root/../../web/inc/dbcon.php");
require "$root/../../web/includedPackages/authy-php/Authy.php";
require_once("$root/../../web/inc/variables.php");
//require_once("$root/../../web/inc/extraFunctions.php");
//include_once("$root/../../web/inc/extraFunctions.php");

//require_once("$root/../inc/dbcon.php");
//require '../includedPackages/authy-php/Authy.php';
//require_once("$root/../inc/variables.php");
//require_once("$root/../inc/extraFunctions.php");

//TODO add back in comments
//if (isset($_GET['actions']) && (strpos($_SERVER["REQUEST_URI"], 'userFunctions.php') === false)){
echo "asdf";
if (isset($_GET['actions']) ){
  $type = $_GET['actions'];
  if ($type == 'login'){
//    $_SESSION['asdf'] = 'asdf';
//    print_r($_SESSION);
    //$_SESSION['username'] = 'asdf';
    //print_r($_SESSION);
    login();
  } else if ($type == 'logout' && isLoggedIn()){
    logout();
  } else if ($type == 'registerUser' && isLoggedIn()){
    registerUser();
  } else if ($type == 'changeUser' && isLoggedIn()){
    changeUser();
  } else if ($type == 'changePassword' && isLoggedIn()){
    changePassword();
  } else if ($type == 'resetPassword'){
    resetPassword();
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

//Logs in the the user and sets session variables
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
    //TODO check if there is an authyID THEN: verifyAuthy
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
      $userInfo = $dbconn->login($user, $pass);
      //if($userInfo !== NULL){
        $_SESSION['name'] = $userInfo['Name'];
        $_SESSION['username'] = $userInfo['Username'];
        $_SESSION['userID'] = $userInfo['ID'];
        $_SESSION['isAdmin'] = $userInfo['IsAdmin'];
        echo "<br> userInfo <br>";
        print_r($userInfo);
        echo "<br> session <br>";
        print_r($_SESSION);
        //
      //}
      $dbconn->close();
    } else { //authy is not right
      header("HTTP/1.0 401 Authy key wrong");
    }
  } else {
    //print_r($_POST);
      //
      print_r($_SESSION);
      //
    echo "nope";
    echo '<br>No username or password entered';
    header("HTTP/1.0 400 Username or password not entered");
    //exit();
  }
}

//Logs out the user and destorys the session variables
//stored by the login system
function logout(){
  //unset($_SESSION['userName']);
  unset($_SESSION['username']);
  unset($_SESSION['isAdmin']);
  $_SESSION = array();
  session_regenerate_id(true);
  session_unset();
  session_destroy();
  setcookie('sid', '', time()-3600);
  session_name('sid');
  session_start();
  header("Location:/");
  exit();
}

//Changes the type of user in the database
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

//Registers a new user to the login system
function registerUser(){
  if (isset($_POST['personName']) && isset($_POST['username'])&& isset($_POST['password']) && isset($_POST['email']) && isAdmin() && isset($_POST['admin'])){
    $personName = $_POST['personName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $admin = ($_POST['admin'] == 'true' ? 1 : 0);
    $authyID = $_POST['authyID'];

    $personName = mysql_real_escape_string($personName);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    $dbconn = new dbconn;
    $dbconn->connect("write");
    $dbconn->registerUser($personName, $username, $password, $email, $admin, $authyID);
    $dbconn->close();
  } else {
    echo 'nothing returned';
    header("HTTP/1.0 403 User Forbidden");
  }
}

//Changes the user's password
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
    //print_r($_POST);
    echo 'nothing returned';
    header("HTTP/1.0 401 User Forbidden");
  }
}

//Changes the password of the user based on the reset token and
//password
function forgotPassword(){
  if(isset($_GET['resetToken']) && isset($_POST['pass']) && isset($_POST['confirmPass']) ){
    //echo $_GET['resetToken'];
    $resetToken = $_GET['resetToken'];
    $pass = $_POST['pass'];
    $otherPass = $_POST['confirmPass'];
    $dbconn = new dbconn;
    $dbconn->connect("write");
    $results = $dbconn->findResetToken($resetToken);
    $dbconn->close();
    if ($results && (strcmp($pass, $otherPass) == 0)){
      //
      //resets the password....
      echo 'Found!';
      $dbconn = new dbconn;
      $dbconn->connect("write");
      $userID = $dbconn->resetChangePassword($pass, $resetToken);
      $results = $dbconn->invalidateResetURL($resetToken, $userID);
      $dbconn->close();
    } else {
      echo 'error! nothing found';
      header("HTTP/1.0 403 User Forbidden");
    }
  } else {
    echo 'nothing returned';
    //print_r($_POST);
    //print_r($_GET);
    header("HTTP/1.0 403 User Forbidden");
  }
}

//Creates a password reset URL for the given user
function resetPassword(){
  if (isset($_POST['username']) && isset($_POST['email'])){
    $username = $_POST['username'];
    $email = $_POST['email'];

    $dbconn = new dbconn;
    $dbconn->connect("write");
    $dbconn->resetPassword($username, $email);
    $dbconn->close();

    echo 'Email sent to: ' . $email . '. Password for username: ' . $username . ' has been reset!';
    echo '<br> Please check your email';
    echo '<br> Click <a href="/">here</a> to go home';
  } else {
    //echo 'not working';
    header("Location:http://doorlock.wrixton.net/");
  }
}

?>
