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
//echo "asdf";
if (isset($_GET['actions']) ){
  $type = $_GET['actions'];
  //echo "asdfadsf \n  ";
  //echo $type;
  if ($type == 'login'){
//    $_SESSION['asdf'] = 'asdf';
//    print_r($_SESSION);
    $_SESSION['username'] = 'asdf';
    //print_r($_SESSION);
    login();
  //} else if ($type == 'logout' && isLoggedIn()){
  } else if ($type == 'logout'){
    logout();
  //} else if ($type == 'changePassword' && isLoggedIn()){
  } else if ($type == 'changePassword'){
    changePassword();
  } else if ($type == 'forgotPassword'){
    forgotPassword();
  } else if ($type == 'lockStatus'){
    lockStatus();
  } else if ($type == 'unlock'){
    unlock();
  } else if ($type == 'lock'){
    lock();
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

function UnAuthError($apiKey){
  header("HTTP/1.0 401 Unauthorized API key invalid");
  header('Content-Type: application/json');
  //return json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
  echo json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
  exit();

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
    //
    //
    //
    //
    return json_encode(array('username' => 'theUser', 'success' => '1/0' ));
  header("HTTP/1.0 401 Unauthorized API key invalid");
}

//Logs out the user and destorys the session variables
//stored by the login system
function logout(){
  //unset($_SESSION['userName']);
  //unset($_SESSION['username']);
  //unset($_SESSION['isAdmin']);
  //$_SESSION = array();
  //session_regenerate_id(true);
  //session_unset();
  //session_destroy();
  //setcookie('sid', '', time()-3600);
  //session_name('sid');
  //session_start();
  //header("Location:/");
  json_encode(array('Logged Out' => 'username', 'success' => '1/0'));
  header("HTTP/1.0 401 Unauthorized API key invalid");
  exit();
}

//Changes the type of user in the database

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
  return json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
  header("HTTP/1.0 401 Unauthorized API key invalid");
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
  return json_encode(array('Reset Password Sent' => 'username', 'success' => '1/0'));
  header("HTTP/1.0 401 Unauthorized API key invalid");
}

//Returns the lock status
function lockStatus(){
  //if(isset($_GET['username']) && isset($_GET['cookie'])){
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    //
    $apiKey = '';
    $user = '';
    $cookie = '';
    if($user != null && $cookie != null){

      return json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => '1/0', 'success' => '1/0'));
    } else {
      //header("HTTP/1.0 401 Unauthorized API key invalid");
      //header('Content-Type: application/json');
      ////return json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
      //echo json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
      UnAuthError($apiKey);
    }
  }
  header("HTTP/1.0 401 Unauthorized API key invalid");
}

//locks the lock
function lock(){
  //if(isset($_GET['username']) && isset($_GET['cookie'])){
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = '';
    $user = '';
    $cookie = '';
    //print_r($_POST);
    if($user != null && $cookie != null){

      echo "asdfasdf FEFFFF";
      //return json_encode(array('Locked Door' => 'Success', 'success' => '1/0'));
      echo json_encode(array('Locked Door' => 'Success', 'success' => '1/0'));
    } else {
      //header("HTTP/1.0 401 Unauthorized API key invalid");
      //header('Content-Type: application/json');
      ////return json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
      //echo json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
      UnAuthError($apiKey);
    }
  }
  //print_r($_POST);
  //print_r($_GET);
  //echo "asdfasfsaf";
  header("HTTP/1.0 401 Unauthorized API key invalid");
}

//unlocks the lock
function unlock(){
  //if(isset($_GET['username']) && isset($_GET['cookie'])){
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = '';
    $user = '';
    $cookie = '';
    if($user != null && $cookie != null){

      return json_encode(array('Unlocked Door' => 'Success', 'success' => '1/0'));
    } else {
      //header("HTTP/1.0 401 Unauthorized API key invalid");
      //header('Content-Type: application/json');
      //return json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
      UnAuthError($apiKey);
    }
  }
  header("HTTP/1.0 401 Unauthorized API key invalid");
}

?>
