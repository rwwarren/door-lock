<?php

require 'Predis/Autoloader.php';
Predis\Autoloader::register();
$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    //'host'   => '10.0.0.1',
    'port'   => 6379,
]);

ini_set("session.hash_function", "sha512");
session_name('sid');


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
  if ($type == 'login'){
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
//  header("Location:/");
  exit();
}

function getApiKey(){
  return isset(getallheaders()['X-DoorLock-Api-Key']) ? getallheaders()['X-DoorLock-Api-Key'] : '';
}


function isValid($apiKey){
  global $client;
//  $value = $client->hgetall('apiKeys');
  $userID = $client->hget('apiKeys', $apiKey);
  //print_r($value);
//  $client = new Predis\Client();

  //$value = $client->hgetall('apiKeys');
  //$value = $client->hget('apiKeys');
  //print_r($value);

  //echo $client;
  //-1 is the all user general one
//  echo "asdf";
//  exit();
  //
  return $userID;
}


function UnAuthError($apiKey = NULL){
  header("HTTP/1.0 401 Unauthorized API key invalid");
  if ($apiKey !== NULL){
    header('Content-Type: application/json');
    //return json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
    echo json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
  }
  exit();
}

//Logs in the the user and sets session variables
function login(){
//  //TODO add check headers and other functions
//  //if(isset($_POST['Username']) && isset($_POST['Password']) /*&& checkHeaders()*/ && isset($_POST['Token'])){
//  if(isset($_POST['Username']) && isset($_POST['Password'])){
//                          //($requiredHeaders == $sentHeaders || isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/")){
//    $user = $_POST['Username'];
//    $pass = $_POST['Password'];
//    $token = $_POST['Token'];
//
//    $user = mysql_real_escape_string($user);
//    $pass = mysql_real_escape_string($pass);
//    $dbconn = new dbconn;
//    //$dbconn->close();
//    //TODO this is the sandbox one
//    //TODO also remove my secret key
//    //TODO add this to the database
//    //TODO check if there is an authyID THEN: verifyAuthy
//    //$authy_id = get from DB;
//    //$dbconn->connect("read");
//    //$authyValid = $dbconn->checkAuthy($user, $token);
//    //$dbconn->close();
//    //TODO add this back in to the check
//    //$verification = $authy_api->verifyToken("$authy_id", "$token");
//    //if($authyValid && $verification->ok()){
//    //TODO above commented out to save testing hassle
//    $test = true;
//    if($test == true){
//      echo '<br> authy token is okay';
//      $dbconn->connect("read");
//      $userInfo = $dbconn->login($user, $pass);
//      //if($userInfo !== NULL){
//        $_SESSION['name'] = $userInfo['Name'];
//        $_SESSION['username'] = $userInfo['Username'];
//        $_SESSION['userID'] = $userInfo['ID'];
//        $_SESSION['isAdmin'] = $userInfo['IsAdmin'];
//        echo "<br> userInfo <br>";
//        print_r($userInfo);
//        echo "<br> session <br>";
//        print_r($_SESSION);
//        //
//      //}
//      $dbconn->close();
//    } else { //authy is not right
//      header("HTTP/1.0 401 Authy key wrong");
//      exit();
//    }
//  } else {
//    //print_r($_POST);
//      //
//      print_r($_SESSION);
//      //
//    echo "nope";
//    echo '<br>No username or password entered';
//    header("HTTP/1.0 400 Username or password not entered");
//    exit();
//  }
//    //
//    //
//    //
//    //
//    return json_encode(array('username' => 'theUser', 'success' => '1/0' ));
//  //header("HTTP/1.0 401 Unauthorized API key invalid");
//  UnAuthError();
  if(isset($_POST['username']) && isset($_POST['password'])){
    //403 error make it work correctly
    $apiKey = getApiKey();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $userID = isValid($apiKey);
    if($username !== null && $password !== null && $userID !== NULL){
      $dbconn = new dbconn;
      $dbconn->connect("read");
      $userInfo = array();
      $userInfo = $dbconn->login($username, $password);
      //TODO set session stuff??
        $_SESSION['name'] = $userInfo['Name'];
        $_SESSION['username'] = $userInfo['Username'];
        $_SESSION['userID'] = $userInfo['ID'];
        $_SESSION['isAdmin'] = $userInfo['IsAdmin'];
        session_write_close();
      header("HTTP/1.0 200 Success, Logged In");
      header('Content-Type: application/json');
      //echo json_encode(array('username' => $username, 'success' => '1/0' ));
      echo json_encode(array('username' => $username, 'success' => '1' ));
      //echo json_encode(array('Logged Out' => $username, 'success' => '1/0'));
      $dbconn->close();
      exit();
    } else {
      header("HTTP/1.0 403 Forbidden");
      header('Content-Type: application/json');
      echo json_encode(array('Invalid Username or Password' => $username, 'success' => '0' ));
      //echo json_encode(array('Logged Out' => $username, 'success' => '1/0'));
      exit();
    }
  }
  UnAuthError();
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
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = getApiKey();
    $username = $_POST['username'];
    $cookie = $_POST['cookie'];
    $userID = isValid($apiKey);
    if($username !== null && $cookie !== null && $userID !== NULL){
      header("HTTP/1.0 200 Success, Logged Out");
      header('Content-Type: application/json');
      //echo json_encode(array('Logged Out' => $username, 'success' => '1/0'));
      echo json_encode(array('Logged Out' => $username, 'success' => '1'));
      exit();
    } else {
      UnAuthError($apiKey);
    }
  }
  UnAuthError();
}

//Changes the user's password
function changePassword(){
  if (isset($_POST['username']) && isset($_POST['old-password']) && isset($_POST['new-password'])
      && isset($_POST['cookie'])){
    $username = $_POST['username'];
    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];
    $cookie = $_POST['cookie'];

    $apiKey = getApiKey();
    $userID = isValid($apiKey);
    if($userID !== NULL){
      //something to do with the user id
      //and change the password
      //unset session stuff
      $dbconn->connect("read");
      //return json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
      header("HTTP/1.0 200 Success, Password Changed");
      header('Content-Type: application/json');
      //echo json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
      //echo json_encode(array('Changed Password' => $username, 'success' => '1/0'));
      //echo json_encode(array('Changed Password' => $username, 'success' => '1/0'));
      echo json_encode(array('Changed Password' => $username, 'success' => '1'));
      $dbconn->close();
      exit();
    } else {
      UnAuthError($apiKey);
    }

    //$username = mysql_real_escape_string($username);
    //$oldPassword = mysql_real_escape_string($oldPassword);
    //$newPassword = mysql_real_escape_string($newPassword);
    //$dbconn = new dbconn;
    //$dbconn->connect("write");
    //$result = $dbconn->changePassword($username, $oldPassword, $newPassword);
    //$dbconn->close();

    //if ($result == 200){
    //  logout();
    //}
    ////header("HTTP/1.0 200 Success, Password Changed");
  }// else {
    //print_r($_POST);
    //echo 'nothing returned';
    //header("HTTP/1.0 401 User Forbidden");
    //UnAuthError();
 // }
  UnAuthError();
}

//Changes the password of the user based on the reset token and
//password
function forgotPassword(){
  if(isset($_POST['username']) && isset($_POST['email'])){
    $apiKey = getApiKey();
    $user = $_POST['username'];
    $email = $_POST['email'];
    $userID = isValid($apiKey);
    //TODO this is the more ish?
    if($userID !== NULL){
//  if(isset($_GET['resetToken']) && isset($_POST['pass']) && isset($_POST['confirmPass']) ){
//    //echo $_GET['resetToken'];
//    $resetToken = $_GET['resetToken'];
//    $pass = $_POST['pass'];
//    $otherPass = $_POST['confirmPass'];
//    $dbconn = new dbconn;
//    $dbconn->connect("write");
//    $results = $dbconn->findResetToken($resetToken);
//    $dbconn->close();
    //if ($results && (strcmp($pass, $otherPass) == 0)){
    //  //
    //  //resets the password....
    //  //echo 'Found!';
    //  //$dbconn = new dbconn;
    //  //$dbconn->connect("write");
    //  //$userID = $dbconn->resetChangePassword($pass, $resetToken);
    //  //$results = $dbconn->invalidateResetURL($resetToken, $userID);
    //  //$dbconn->close();
    //  header("HTTP/1.0 200 Success");
    //  header('Content-Type: application/json');
    //  return json_encode(array('Reset Password Sent' => $username, 'success' => '1/0'));
    //} else {
    //  echo 'error! nothing found';
    //  header("HTTP/1.0 403 User Forbidden");
    //  exit();
    //}
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      //return json_encode(array('Reset Password Sent' => $username, 'success' => '1/0'));
      //echo json_encode(array('Reset Password Sent' => $user, 'success' => '1/0'));
      echo json_encode(array('Reset Password Sent' => $user, 'success' => '1'));
      exit();
    } else {
      UnAuthError($apiKey);
    }
  }
  UnAuthError();
}

//Returns the lock status
function lockStatus(){
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = getApiKey();
    $user = $_POST['username'];
    $cookie = $_POST['cookie'];
    $userID = isValid($apiKey);
    //is logged in?
    if($user !== null && $cookie !== null && $userID !== NULL){
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      $lockStatus = '1/0';

      //return json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => '1/0', 'success' => '1/0'));
      //echo json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => '1/0', 'success' => '1/0'));
      echo json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => $lockStatus, 'success' => '1'));
      exit();
    } else {
      UnAuthError($apiKey);
    }
  }
  UnAuthError();
}

//locks the lock
function lock(){
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = getApiKey();
    $user = $_POST['username'];
    $cookie = $_POST['cookie'];
    $userID = isValid($apiKey);
    //is logged in?
    if($user !== null && $cookie !== null && $userID !== NULL){
      //return json_encode(array('Locked Door' => 'Success', 'success' => '1/0'));
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      //echo json_encode(array('Locked Door' => 'Success', 'success' => '1/0'));
      echo json_encode(array('Locked Door' => 'Success', 'success' => '1'));
      exit();
    } else {
      UnAuthError($apiKey);
    }
  }
  UnAuthError();
}

//unlocks the lock
function unlock(){
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = getApiKey();
    $user = $_POST['username'];
    $cookie = $_POST['cookie'];
    $userID = isValid($apiKey);
    //is logged in?
    if($user !== null && $cookie !== null && $userID !== NULL){
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      //return json_encode(array('Unlocked Door' => 'Success', 'success' => '1/0'));
      //echo json_encode(array('Unlocked Door' => 'Success', 'success' => '1/0'));
      echo json_encode(array('Unlocked Door' => 'Success', 'success' => '1'));
      exit();
    } else {
      UnAuthError($apiKey);
    }
  }
  UnAuthError();
}

?>
