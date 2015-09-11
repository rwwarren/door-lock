<?php
error_reporting(E_ALL);
//ini_set('error_log','/Users/ryan/Documents/door-lock/web/php.log');
ini_set('error_log', '/Users/ryan/Documents/door-lock/api/php.log');

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require_once __DIR__ . '/../vendor/predis/predis/src/Autoloader.php';
Predis\Autoloader::register();
$client = new Predis\Client([
  'scheme' => 'tcp',
  'host' => '127.0.0.1',
  'port' => 6379,
]);

ini_set("session.hash_function", "sha512");
session_name('sid');

session_start();

if (isset($_GET['actions'])) {
  $type = $_GET['actions'];
  switch ($type) {
    case $type == 'login':
      login();
      break;
    case $type == 'logout' :
      logout();
      break;
    case $type == 'isLoggedIn':
      isLoggedIn();
      break;
    case $type == 'isAdmin':
      isAdmin();
      break;
    case $type == 'getUserInfo':
      getUserInfo();
      break;
    case $type == 'getAllUsers':
      getAllUsers();
      break;
    case $type == 'registerUser' && isLoggedIn():
      registerUser();
      break;
    case $type == 'changeUserType' && currentlyLoggedIn():
      changeUserType();
      break;
    case $type == 'updateUserInfo' && isLoggedIn():
      updateUserInfo();
      break;
    case $type == 'forgotPassword':
      forgotPassword();
      break;
    case $type == 'resetPassword':
      resetPassword();
      break;
    case $type == 'lockStatus':
      lockStatus();
      break;
    case $type == 'lock':
      lock();
      break;
    case $type == 'unlock':
      unlock();
      break;
    case $type == 'config':
      config();
      break;
    default:
      echo "You don't have permission to call that function so back off!";
      header("HTTP/1.0 403 User Forbidden");
      exit();
  }
} else {
  echo "improper request";
  exit();
}

function incorrectPostError() {
  //TODO fix this
  print "some incorrect post error with the post variables";
}

function checkConfirmation($confirmation) {
  //TODO check the confirmation
}

function logoutError() {
  //todo something here?
}

function notLoggedInError() {
  //Todo something like this
}

//Logs in the the user and sets session variables
function login() {
  if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['sid'])) {
    //403 error make it work correctly
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cookie = $_POST['sid'];
    if ($username !== null && $password !== null && $cookie !== null) {
      $dbconn = new dbconn("read");
//      $dbconn->connect("read");
      //TODO pass in the api key?
      $userInfo = $dbconn->login($username, $password);
      //TODO set session stuff??
      if ($userInfo != false) {
        $name = $userInfo['Name'];
        $isAdmin = $userInfo['IsAdmin'];
        //TODO: check if this is really needed
        $uid = $userInfo['ID'];
        global $client;
        //TODO check the confirmations
        $confirmation = $client->HSET("loggedInUsers:$cookie", "name", "$name");
        checkConfirmation($confirmation);
        $confirmation = $client->HSET("loggedInUsers:$cookie", "username", "$username");
        checkConfirmation($confirmation);
        $confirmation = $client->HSET("loggedInUsers:$cookie", "UID", "$uid");
        checkConfirmation($confirmation);
        $confirmation = $client->HSET("loggedInUsers:$cookie", "admin", "$isAdmin");
        checkConfirmation($confirmation);
        session_write_close();
        header("HTTP/1.0 200 Success, Logged In");
        header('Content-Type: application/json');
        echo json_encode(array('username' => $username, 'name' => $name, 'isAdmin' => $isAdmin, 'success' => '1'));
        exit();
      }
    }
    header("HTTP/1.0 403 Forbidden");
    header('Content-Type: application/json');
    echo json_encode(array('Invalid Username or Password' => $username, 'success' => '0'));
    exit();
  }
  //TODO better error. nothing is passed in here is the error
  incorrectPostError();
}

//Logs out the user and destorys the session variables
//stored by the login system
function logout() {
  $cookie = $_POST['sid'];
  if ($cookie !== null && currentlyLoggedIn()) {
    global $client;
    $username = $client->HGET("loggedInUsers:$cookie", "name");
    checkConfirmation($username);
    $confirmation = $client->HDEL("loggedInUsers:$cookie", "name");
    checkConfirmation($confirmation);
    $confirmation = $client->HDEL("loggedInUsers:$cookie", "username");
    checkConfirmation($confirmation);
    $confirmation = $client->HDEL("loggedInUsers:$cookie", "UID");
    checkConfirmation($confirmation);
    $confirmation = $client->HDEL("loggedInUsers:$cookie", "admin");
    checkConfirmation($confirmation);
    if ($confirmation === 0) {
      echo "error";
      exit();
    }
    //Might work below
    unset($_SESSION['userName']);
    unset($_SESSION['username']);
    unset($_SESSION['isAdmin']);
    $_SESSION = array();
    session_regenerate_id(true);
    session_unset();
    session_destroy();
    setcookie('sid', '', time() - 3600);
    session_name('sid');
    session_start();

    header("HTTP/1.0 200 Success, Logged Out");
    header('Content-Type: application/json');
    echo json_encode(array('Logged Out' => $username, 'success' => '1'));
    exit();
  } else {
    logoutError();
  }
  incorrectPostError();
}

//Make note this is internal use make protected or private somehow
//TODO find usages and remove?
function currentlyLoggedIn() {
  global $client;
  $cookie = $_POST['sid'];
  $username = $client->hget("loggedInUsers:$cookie", "username");
  return $username !== null;
}

function isLoggedIn() {
  $cookie = $_POST['sid'];
  global $client;
  $name = $client->hget("loggedInUsers:$cookie", "name");
  $username = $client->hget("loggedInUsers:$cookie", "username");
  $admin = $client->hget("loggedInUsers:$cookie", "admin");
  if (strlen($name) > 0 && strlen($username) > 0 && strlen($admin) > 0) {
//    getMac();
    echo json_encode(array('LoggedIn' => $username, 'Username' => $username, 'Name' => $name, 'IsAdmin' => $admin, 'success' => '1'));
    exit();
  }
  echo json_encode(array('Error' => 'User not logged in', 'success' => '0'));
  exit();
}

//TODO do I want this?
//function getMac() {
//  ob_start();
//  system('ifconfig ');
//  $mycom = ob_get_contents();
//  ob_clean();
//
//  $findme = "ether";
//  $pmac = strpos($mycom, $findme);
//  $mac = substr($mycom, ($pmac + 6), 17);
//
////echo "My Mac!!! " . $mac . " \n";
////echo "Ip addr " .$_SERVER['REMOTE_ADDR'] . " \n";
//}

function isAdmin() {
  $cookie = $_POST['sid'];
  global $client;
  $isAdmin = $client->hget("loggedInUsers:$cookie", "admin");
  echo json_encode(array("admin" => $isAdmin));
  exit();
}

function getUserInfo() {
  $cookie = $_POST['sid'];
  global $client;
  $username = $client->hget("loggedInUsers:$cookie", "username");
  $dbconn = new dbconn("read");
  $result = $dbconn->getUserInfo($username);
  $result["success"] = "1";
  header("HTTP/1.0 200 Success");
  header('Content-Type: application/json');
  echo json_encode($result, true);
}

function getAllUsers() {
  if (isset($_POST['sid'])) {
    $cookie = $_POST['sid'];
    global $client;
    $isAdmin = $client->hget("loggedInUsers:$cookie", "admin");
    if ($isAdmin !== "1") {
      echo "not admin";
      exit();
    }
    $dbconn = new dbconn("read");
    $activeUsers = $dbconn->getActiveUsers();
    $inactiveUsers = $dbconn->getInactiveUsers();
    $admins = $dbconn->getAdmins();
    header("HTTP/1.0 200 Success");
    header('Content-Type: application/json');
    echo json_encode(array('success' => '1', 'InactiveUsers' => $inactiveUsers, "ActiveUsers" => $activeUsers, "Admins" => $admins), true);
    exit();
  }
  incorrectPostError();
}

function changeUserType() {
  //TODO fix this
  $isAdmin = isAdmin()['admin'] === "1";
  if (isset($_POST['user']) && isset($_POST['type']) && $isAdmin /*&& checkHeaders()*/) {
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

function updateUserInfo() {
  //TODO fix this
  //TODO update this to use variable binding
  if (isset($_SESSION['username']) && isset($_POST['oldPwd']) && isset($_POST['newPwd']) && isset($_POST['authy']) &&
    isset($_POST['card']) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['confNewPass'])
  ) {
    $username = $_SESSION['username'];
    $oldPassword = mysql_real_escape_string($_POST['oldPwd']);
    $newPassword = mysql_real_escape_string($_POST['newPwd']);
    $confNewPassword = mysql_real_escape_string($_POST['confNewPass']);
    $authy = mysql_real_escape_string($_POST['authy']);
    $card = mysql_real_escape_string($_POST['card']);
    $email = mysql_real_escape_string($_POST['email']);
    $name = mysql_real_escape_string($_POST['name']);
    if ($newPassword !== $confNewPassword) {
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
      if ($result == 202) {
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

function registerUser() {
  //TODO fix this
  if (isset($_POST['personName']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isAdmin() && isset($_POST['admin'])) {
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
function forgotPassword() {
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
  if (isset($_GET['resetToken']) && isset($_POST['pass']) && isset($_POST['confirmPass'])) {
    $resetToken = $_GET['resetToken'];
    $pass = $_POST['pass'];
    $otherPass = $_POST['confirmPass'];
    $dbconn = new dbconn("write");
    //$dbconn->connect("write");
    $results = $dbconn->findResetToken($resetToken);
    //$dbconn->close();
    if ($results && (strcmp($pass, $otherPass) == 0)) {
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

function resetPassword() {
  //TODO CHANGE THIS
  if (isset($_POST['username']) && isset($_POST['email'])) {
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
function lockStatus() {
  //TODO get an actual status
  if (isset($_POST['sid'])) {
    $sid = $_POST['sid'];
    //TODO check is logged in?
//    checkLogin();
    if ($sid !== NULL) {
      //if($user !== null && $sid !== null && $userID !== NULL){
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      $lockStatus = '1/0';

      //return json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => '1/0', 'success' => '1/0'));
      //echo json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => '1/0', 'success' => '1/0'));
      echo json_encode(array('Status' => 'Unlocked/Locked', 'isLocked' => $lockStatus, 'success' => '1'));
      exit();
    }
  }
  incorrectPostError();
}

//locks the lock
function lock() {
  //TODO rewrite this with sid and check login
  if (isset($_POST['uid'])) {
//  if(isset($_POST['username']) && isset($_POST['cookie'])){
    $uid = $_POST['uid'];
    //is logged in?
    if ($uid !== null && strlen($uid) > 0) {
      $dbconn = new dbconn("read");
      $userID = $dbconn->checkCardID($uid);
      if ($userID === null) {
        header("HTTP/1.0 403 Forbidden");
        header('Content-Type: application/json');
        echo json_encode(array('Locked Door' => 'Failed', 'success' => '0'));
        exit();
      }
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      echo json_encode(array('Locked Door' => 'Success', 'success' => '1'));
      exit();
    } else {
      notLoggedInError();
      exit();
    }
  }
//  echo "in the login function";
//  print_r($_REQUEST);
  incorrectPostError();
  exit();
}

//unlocks the lock
function unlock() {
  //TODO use sid and rewrite
  if (isset($_POST['uid'])) {
    $uid = $_POST['uid'];
//    $cookie = $_POST['cookie'];
//    $userID = isValid($apiKey);
    //is logged in?
    if ($uid !== null && strlen($uid) > 0) {
      $dbconn = new dbconn("read");
      $userID = $dbconn->checkCardID($uid);
      if ($userID === null) {
        header("HTTP/1.0 403 Forbidden");
        header('Content-Type: application/json');
        echo json_encode(array('Locked Door' => 'Failed', 'success' => '0'));
        exit();
      }
//    if($user !== null && $cookie !== null && $userID !== NULL){
      header("HTTP/1.0 200 Success");
      header('Content-Type: application/json');
      //return json_encode(array('Unlocked Door' => 'Success', 'success' => '1/0'));
      //echo json_encode(array('Unlocked Door' => 'Success', 'success' => '1/0'));
      echo json_encode(array('Unlocked Door' => 'Success', 'success' => '1'));
      exit();
    } else {
      notLoggedInError();
    }
  }
  incorrectPostError();
}

function config() {
  if (isset($_POST['sid'])) {
    $cookie = $_POST['sid'];
    global $client;
    $isAdmin = $client->hget("loggedInUsers:$cookie", "admin");
    if ($isAdmin !== "1") {
      echo "not admin";
      exit();
    }
    //TODO figure out how to udage config
    header("HTTP/1.0 200 Success");
    header('Content-Type: application/json');
    echo json_encode(parse_ini_file("../properties/config.ini"));
  }
}

