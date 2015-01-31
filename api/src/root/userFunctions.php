<?php

require_once __DIR__.'/../vendor/predis/predis/src/Autoloader.php';
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
require_once("$root/../inc/dbcon.php");
//require "$root/../../web/includedPackages/authy-php/Authy.php";
//require_once("Authy/Authy.php");
//require_once("$root/../../../web/src/vendor/autoload.php");
//require_once("$root/../../../web/src/inc/variables.php");
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
  //TODO make this a switch
  if ($type == 'login'){
    login();
  //} else if ($type == 'logout' && isLoggedIn()){
  } else if ($type == 'logout'){
    logout();
  //} else if ($type == 'changePassword' && isLoggedIn()){
  } else if ($type == 'isLoggedIn'){
    isLoggedIn();
  } else if ($type == 'isAdmin'){
    isAdmin();
  } else if ($type == 'getUserInfo'){
    getUserInfo();
  } else if ($type == 'getAllUsers'){
    getAllUsers();
//  } else if ($type == 'changePassword'){
//    changePassword();
  } else if ($type == 'registerUser' && isLoggedIn()){
    registerUser();
  } else if ($type == 'changeUserType' && isLoggedIn()){
    changeUserType();
  } else if ($type == 'updateUserInfo' && isLoggedIn()){
    updateUserInfo();
  } else if ($type == 'forgotPassword'){
    forgotPassword();
  } else if ($type == 'resetPassword'){
    resetPassword();
  } else if ($type == 'lockStatus'){
    lockStatus();
  } else if ($type == 'lock'){
    lock();
  } else if ($type == 'unlock'){
    unlock();
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
//  print_r($_POST);
  if(isset($_POST['username']) && isset($_POST['password'])){
    //403 error make it work correctly
    $apiKey = getApiKey();
//    echo $apiKey;
    $username = $_POST['username'];
    $password = $_POST['password'];
    //$username = mysql_real_escape_string($username);
    //$password = mysql_real_escape_string($password);
    $userID = isValid($apiKey);
    //$userID = 1;//isValid($apiKey);
    if($username !== null && $password !== null && $userID !== NULL){
      $dbconn = new dbconn("read");
//      $dbconn->connect("read");
//      $userInfo = array();
      $userInfo = $dbconn->login($username, $password);
//      echo "asdf tHERE: " . $userInfo;
      //TODO set session stuff??
      if($userInfo != false) {
//        $_SESSION['name'] = $userInfo['Name'];
//        $_SESSION['username'] = $userInfo['Username'];
//        $_SESSION['userID'] = $userInfo['ID'];
//        $_SESSION['isAdmin'] = $userInfo['IsAdmin'];
//        print_r($_POST);
//        print_r(getallheaders());
//        echo getallheaders()['cookie'];
        $cookie = getallheaders()['sid'];
        $name = $userInfo['Name'];
        $isAdmin = $userInfo['IsAdmin'];
        //TODO: check if this is really needed
        $uid = $userInfo['ID'];
        global $client;
//  $value = $client->hgetall('apiKeys');
//        $userID = $client->hset('loggedInUsers', $apiKey);
        $confirmation = $client->HSET("loggedInUsers:$cookie", "name", "$name");
        $confirmation = $client->HSET("loggedInUsers:$cookie", "username", "$username");
        $confirmation = $client->HSET("loggedInUsers:$cookie", "UID", "$uid");
        $confirmation = $client->HSET("loggedInUsers:$cookie", "admin", "$isAdmin");
//        $confirmation = $client->HSET("loggedInAdmins:$cookie", "Admin", "$isAdmin");
//        $userID = $client->HMSET('loggedInUsers', array("sid" => "$cookie"));
//        $userID = $client->HMSET('loggedInUsers', $apiKey);
//        print_r($_SESSION);
//        print_r(session_get_cookie_params());
        session_write_close();
        header("HTTP/1.0 200 Success, Logged In");
        header('Content-Type: application/json');
        //echo json_encode(array('username' => $username, 'success' => '1/0' ));
        echo json_encode(array('username' => $username, 'name' => $name, 'isAdmin' => $isAdmin, 'success' => '1' ));
        exit();
        //echo json_encode(array('Logged Out' => $username, 'success' => '1/0'));
      }

//      $dbconn->close();
      header("HTTP/1.0 403 Forbidden");
      header('Content-Type: application/json');
      echo json_encode(array('Invalid Username or Password' => $username, 'success' => '0' ));
      exit();
    } else {
      header("HTTP/1.0 403 Forbidden");
      header('Content-Type: application/json');
      echo json_encode(array('Invalid Username or Password' => $username, 'success' => '0' ));
      //echo json_encode(array('Logged Out' => $username, 'success' => '1/0'));
      exit();
    }
  }
  echo "got here";
  UnAuthError();
}

//Logs out the user and destorys the session variables
//stored by the login system
function logout(){
  if(true){
//  if(isset($_POST['username']) && isset($_POST['cookie'])){
//    $apiKey = getApiKey();
    $username = "test";//$_POST['username'];
//    $username = $_POST['username'];
    $cookie = "none?";//$_POST['cookie'];
//    $cookie = $_POST['cookie'];
    $userID = 1;//isValid($apiKey);
//    $userID = isValid($apiKey);
    if($username !== null && $cookie !== null && $userID !== NULL){
      $cookie = getallheaders()['sid'];
//      $name = $userInfo['Name'];
      global $client;
//      $confirm = $client->HDEL('loggedInUsers', $cookie);
      $confirmation = $client->HDEL("loggedInUsers:$cookie", "name");
      $confirmation = $client->HDEL("loggedInUsers:$cookie", "username");
      $confirmation = $client->HDEL("loggedInUsers:$cookie", "UID");
      $confirmation = $client->HDEL("loggedInUsers:$cookie", "admin");
      if($confirmation === 0){
        echo "error";
        exit();
      }

      //TODO remove from redis
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

function isLoggedIn() {
  $cookie = getallheaders()['sid'];
//  print_r(getallheaders());
//  $name = $userInfo['Name'];
  global $client;
  $name = $client->hget("loggedInUsers:$cookie", "name");
  $username = $client->hget("loggedInUsers:$cookie", "username");
  $admin = $client->hget("loggedInUsers:$cookie", "admin");
  if(strlen($name) > 0){
//    return those
//    $_SESSION['name'] = $decoded['name'];
//    $_SESSION['username'] = $decoded['username'];
//    $_SESSION['isAdmin'] = $decoded['isAdmin'];
    echo json_encode(array('LoggedIn' => $username, 'Name' => $name, 'IsAdmin' => $admin, 'success' => '1'));
    exit();
  }
  echo json_encode(array('Error' => 'User not logged in', 'success' => '0'));
  exit();
//  return json_encode(array('LoggedIn' => $userID, 'success' => '1'));

//  echo $userID;
//  echo $userID;
//  exit();
//  return sizeof($userID) > 3 ? 1 : 0;
//  $userID = $client->HMGET('loggedInUsers', array("$cookie" => "$name"));
}

function isAdmin() {
  $cookie = getallheaders()['sid'];
//  $name = $userInfo['Name'];
  global $client;
//  $value = $client->hgetall('apiKeys');
//        $userID = $client->hset('loggedInUsers', $apiKey);
  $isAdmin = $client->hget("loggedInUsers:$cookie", "admin");
  echo json_encode(array("admin" => $isAdmin));
  exit();
//  $userID = $client->HMSET('loggedInUsers', array("$cookie" => "$name"));
}

function getUserInfo(){
  //TODO get the user information
  $cookie = getallheaders()['sid'];
  global $client;
  $username = $client->hget("loggedInUsers:$cookie", "username");
  $dbconn = new dbconn("read");
  $result = $dbconn->getUserInfo($username);
  //name
  //email
  //cardID
  //authyId
//  print_r($result);
  header("HTTP/1.0 200 Success");
  header('Content-Type: application/json');
  echo json_encode($result, true);
//  return $result;
}

function getAllUsers(){
  //TODO get all the users
  return array('InactiveUsers' => array(""), "ActiveUsers" => array(""), "Admins" => array(""));
}

function changeUserType(){
  //TODO fix this
  if(isset($_POST['user']) && isset($_POST['type']) && isAdmin() /*&& checkHeaders()*/){
    $user = $_POST['user'];
    $type = $_POST['type'];
    $user = mysql_real_escape_string($user);
    $dbconn = new dbconn("write");
    //$dbconn->connect("write");
    $dbconn->changeUser($user, $type);
    //$dbconn->close();
  } else {
    echo "nope";
    echo '<br>No username entered';
    header("HTTP/1.0 403 User Forbidden");
  }
}

function updateUserInfo(){
  //TODO fix this
  if (isset($_SESSION['username']) && isset($_POST['oldPwd']) && isset($_POST['newPwd']) && isset($_POST['authy']) &&
    isset($_POST['card']) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['confNewPass']) ){
    $username = $_SESSION['username'];
    $oldPassword = mysql_real_escape_string($_POST['oldPwd']);
    $newPassword = mysql_real_escape_string($_POST['newPwd']);
    $confNewPassword = mysql_real_escape_string($_POST['confNewPass']);
    $authy = mysql_real_escape_string($_POST['authy']);
    $card = mysql_real_escape_string($_POST['card']);
    $email = mysql_real_escape_string($_POST['email']);
    $name = mysql_real_escape_string($_POST['name']);
    if($newPassword !== $confNewPassword){
      echo 'new password and confirmed new password are not equal';
      return false;
      exit();
    } else {
//      $username = mysql_real_escape_string($username);
//      $oldPassword = mysql_real_escape_string($oldPassword);
//      $newPassword = mysql_real_escape_string($newPassword);
      $dbconn = new dbconn("write");
      //$dbconn->connect("write");
      $result = $dbconn->updateUserInfo($username, $oldPassword, $newPassword, $confNewPassword, $authy, $card, $email, $name);
      //TODO this is the function name below
      //$dbconn->close();
      //print_r($_SERVER);
      if($result == 202){
        logout();
      }
      exit();
    }
  } else {
    print_r($_POST);
    echo 'nothing returned';
    header("HTTP/1.0 401 User Forbidden");
    exit();
  }

}

function registerUser(){
  //TODO fix this
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
    $dbconn = new dbconn("write");
    //$dbconn->connect("write");
    $dbconn->registerUser($personName, $username, $password, $email, $admin, $authyID);
    //$dbconn->close();
  } else {
    echo 'nothing returned';
    header("HTTP/1.0 403 User Forbidden");
  }
}


////Changes the user's password
//function changePassword(){
//  if (isset($_POST['username']) && isset($_POST['old-password']) && isset($_POST['new-password'])
//      && isset($_POST['cookie'])){
//    $username = $_POST['username'];
//    $oldPassword = $_POST['old-password'];
//    $newPassword = $_POST['new-password'];
//    $cookie = $_POST['cookie'];
//
//    $apiKey = getApiKey();
//    $userID = isValid($apiKey);
//    if($userID !== NULL){
//      //something to do with the user id
//      //and change the password
//      //unset session stuff
//      $dbconn = new dbconn("read");
////      $dbconn->connect("read");
//      //$dbconn->changePassword($username, $oldPassword, $newPassword);
//      //return json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
//      header("HTTP/1.0 200 Success, Password Changed");
//      header('Content-Type: application/json');
//      //echo json_encode(array('Changed Password' => 'username', 'success' => '1/0'));
//      //echo json_encode(array('Changed Password' => $username, 'success' => '1/0'));
//      //echo json_encode(array('Changed Password' => $username, 'success' => '1/0'));
//      echo json_encode(array('Changed Password' => $username, 'success' => '1'));
////      $dbconn->close();
//      exit();
//    } else {
//      UnAuthError($apiKey);
//    }
//  }
//  UnAuthError();
//}

//Changes the password of the user based on the reset token and
//password
function forgotPassword(){
//  if(isset($_POST['username']) && isset($_POST['email'])){
//    $apiKey = getApiKey();
//    $username = $_POST['username'];
//    $email = $_POST['email'];
//    $userID = isValid($apiKey);
//    //TODO this is the more ish?
//    if($userID !== NULL){
////      $dbconn = new dbconn;
////      $dbconn->connect("write");
////      $dbconn->resetPassword($username, $email);
////      $dbconn->close();
//      header("HTTP/1.0 200 Success");
//      header('Content-Type: application/json');
//      //return json_encode(array('Reset Password Sent' => $username, 'success' => '1/0'));
//      //echo json_encode(array('Reset Password Sent' => $user, 'success' => '1/0'));
//      echo json_encode(array('Reset Password Sent' => $user, 'success' => '1'));
//      exit();
//    } else {
//      UnAuthError($apiKey);
//    }
//  }
//  UnAuthError();
  //TODO CHANGE THIS
  if(isset($_GET['resetToken']) && isset($_POST['pass']) && isset($_POST['confirmPass']) ){
    $resetToken = $_GET['resetToken'];
    $pass = $_POST['pass'];
    $otherPass = $_POST['confirmPass'];
    $dbconn = new dbconn("write");
    //$dbconn->connect("write");
    $results = $dbconn->findResetToken($resetToken);
    //$dbconn->close();
    if ($results && (strcmp($pass, $otherPass) == 0)){
      //resets the password....
      echo 'Found!';
      $dbconn = new dbconn("write");
      //$dbconn->connect("write");
      $userID = $dbconn->resetChangePassword($pass, $resetToken);
      $results = $dbconn->invalidateResetURL($resetToken, $userID);
      //$dbconn->close();
      return true;
      exit();
    } else {
      echo 'error! nothing found';
      header("HTTP/1.0 403 User Forbidden");
      exit();
    }
  } else {
    echo 'nothing returned';
    header("HTTP/1.0 403 User Forbidden");
    exit();
  }
}

function resetPassword(){
  //TODO CHANGE THIS
  if (isset($_POST['username']) && isset($_POST['email'])){
    $username = $_POST['username'];
    $email = $_POST['email'];

    $dbconn = new dbconn("write");
    //$dbconn->connect("write");
    $dbconn->resetPassword($username, $email);
    //$dbconn->close();

    echo 'Email sent to: ' . $email . '. Password for username: ' . $username . ' has been reset!';
    echo '<br> Please check your email';
    echo '<br> Click <a href="/">here</a> to go home';
  } else {
    header("Location: http://$_SERVER[HTTP_HOST]");
  }
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
