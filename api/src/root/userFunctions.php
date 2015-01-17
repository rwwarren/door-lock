<?php

require __DIR__.'/../../../web/src/vendor/predis/predis/src/Autoloader.php';
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
require_once("$root/../../../web/src/inc/dbcon.php");
//require "$root/../../web/includedPackages/authy-php/Authy.php";
//require_once("Authy/Authy.php");
require_once("$root/../../../web/src/vendor/autoload.php");
require_once("$root/../../../web/src/inc/variables.php");
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

//Returns the api key
function getApiKey(){
  return isset(getallheaders()['X-DoorLock-Api-Key']) ? getallheaders()['X-DoorLock-Api-Key'] : '';
}

//Checks if the api key is valid
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

//Returns the Unauthorized Api with headers
function UnAuthError($apiKey = NULL){
  header("HTTP/1.0 401 Unauthorized API key invalid");
    header('Content-Type: application/json');
  if ($apiKey !== NULL){
    //header('Content-Type: application/json');
    //return json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
    echo json_encode(array('Invalid API Key' => $apiKey, 'success' => '0'));
    exit();
  }
    echo json_encode(array('Invalid API Key!!!' => $apiKey, 'success' => '0'));
  exit();
}

//Logs in the the user and sets session variables
function login(){
//  print_r(getallheaders());
  if(isset($_POST['username']) && isset($_POST['password'])){
    //403 error make it work correctly
    $apiKey = getApiKey();
//    echo $apiKey;
    $username = $_POST['username'];
    $password = $_POST['password'];
    //$username = mysql_real_escape_string($username);
    //$password = mysql_real_escape_string($password);
    $userID = isValid($apiKey);
    if($username !== null && $password !== null && $userID !== NULL){
      $dbconn = new dbconn("read");
//      $dbconn->connect("read");
      $userInfo = array();
      $userInfo = $dbconn->login($username, $password);
      //TODO set session stuff??
      if($userInfo !== null) {
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
      }
//      $dbconn->close();
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
  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $apiKey = getApiKey();
    $username = $_POST['username'];
    $cookie = $_POST['cookie'];
    $userID = isValid($apiKey);
    if($username !== null && $cookie !== null && $userID !== NULL){

      //Might work below
      unset($_SESSION['userName']);
      unset($_SESSION['username']);
      unset($_SESSION['isAdmin']);
      $_SESSION = array();
      session_regenerate_id(true);
      session_unset();
      session_destroy();
      setcookie('sid', '', time()-3600);
      session_name('sid');
      session_start();

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
      $dbconn = new dbconn("read");
//      $dbconn->connect("read");
      //$dbconn->changePassword($username, $oldPassword, $newPassword);
      //return json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
      header("HTTP/1.0 200 Success, Password Changed");
      header('Content-Type: application/json');
      //echo json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
      //echo json_encode(array('Changed Password' => $username, 'success' => '1/0'));
      //echo json_encode(array('Changed Password' => $username, 'success' => '1/0'));
      echo json_encode(array('Changed Password' => $username, 'success' => '1'));
//      $dbconn->close();
      exit();
    } else {
      UnAuthError($apiKey);
    }
  }
  UnAuthError();
}

//Changes the password of the user based on the reset token and
//password
function forgotPassword(){
  if(isset($_POST['username']) && isset($_POST['email'])){
    $apiKey = getApiKey();
    $username = $_POST['username'];
    $email = $_POST['email'];
    $userID = isValid($apiKey);
    //TODO this is the more ish?
    if($userID !== NULL){
//      $dbconn = new dbconn;
//      $dbconn->connect("write");
//      $dbconn->resetPassword($username, $email);
//      $dbconn->close();
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
