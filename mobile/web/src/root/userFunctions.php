<?php

ini_set("session.hash_function", "sha512");
session_name('sid');
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../../inc/variables.php");
include_once("$root/../../../../web/src/vendor/door-lock/api-client/src/root/apiClient.php");
if (isset($_GET['actions']) && (strpos($_SERVER["REQUEST_URI"], 'userFunctions.php') === false)){
  //TODO make sure that request comes from localhost
  $type = $_GET['actions'];
  if ($type === 'login'){
    login();
  } else if ($type == 'logout'){
    logout();
  } else if ($type == 'checkLogin'){
    checkLogin();
  } else if ($type == 'lockStatus'){
    lockStatus();
  } else if ($type == 'userInfo'){
    userInfo();
  } else if ($type == 'admin'){
    admin();
  } else if ($type == 'registerUser' && isLoggedIn()){
    registerUser();
  } else if ($type == 'changeUser' && isLoggedIn()){
    changeUser();
  } else if ($type == 'changeUserInfo' && isLoggedIn()){
    changeUserInfo();
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
  header("Location: http://$_SERVER[HTTP_X_FORWARDED_SERVER]");
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
  if(isset($_POST['Username']) && isset($_POST['Password']) /*&& checkHeaders()*/ && isset($_POST['Token']) && isset($_POST['sid'])){
    $user = $_POST['Username'];
    $pass = $_POST['Password'];
    $token = $_POST['Token'];
    global $root;
    $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
    $results = $apiClient->login($_POST, $_POST['sid']);
    if($results !== null){
      $_SESSION['username'] = $_POST['Username'];
      $_SESSION['name'] = $results['name'];
      $_SESSION['username'] = $results['username'];
      $_SESSION['isAdmin'] = $results['isAdmin'];
      header("HTTP/1.0 200 Login Successful");
      echo json_encode("success", true);
      exit();
    }
  } else {
    header("HTTP/1.0 400 Username or password not entered");
    echo "nope";
    echo '<br>No username or password entered';
    exit();
  }
}

function checkLogin(){
  $apiClient = new ApiClient\ApiClient();
  if(!isset($_POST['sid']) || strlen($_POST['sid']) < 1) {
    header("HTTP/1.0 400 sid not entered");
    echo "error sid not entered";
    exit();
  }
  $results = $apiClient->isLoggedIn($_POST['sid']);
  if($results['success'] === "1") {
    header("HTTP/1.0 200 logged in");
    echo json_encode($results, true);
    //TODO get this not to open another page
    exit();
  } else {
    header("HTTP/1.0 400 not logged in");
    echo "error. not logged in";
  }
}

function userInfo(){
  global $root;
  $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
  if(!isset($_POST['sid']) || strlen($_POST['sid']) < 1) {
    header("HTTP/1.0 400 sid not entered");
    echo "error sid not entered";
    exit();
  }
  $results = $apiClient->getUserInfo($_POST['sid']);
  if($results['success'] === "1") {
    header("HTTP/1.0 200 logged in");
    echo json_encode($results, true);
    //TODO get this not to open another page
    exit();
  } else {
    header("HTTP/1.0 400 not logged in");
    echo "error. not logged in";
  }
}

function lockStatus(){
  global $root;
  $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
  if(!isset($_POST['sid']) || strlen($_POST['sid']) < 1) {
    header("HTTP/1.0 400 sid not entered");
    echo "error sid not entered";
    exit();
  }
  $results = $apiClient->lockStatus($_POST['sid']);
  if($results['success'] === "1") {
    header("HTTP/1.0 200 logged in");
    echo json_encode($results, true);
    //TODO get this not to open another page
    exit();
  } else {
    header("HTTP/1.0 400 not logged in");
    echo "error. not logged in";
  }
}

function admin(){
  global $root;
  $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
  if(!isset($_POST['sid']) || strlen($_POST['sid']) < 1) {
    header("HTTP/1.0 400 sid not entered");
    echo "error sid not entered";
    exit();
  }
  $results = $apiClient->getAllUsers($_POST['sid']);
  if($results['success'] === "1") {
    header("HTTP/1.0 200 logged in");
    echo json_encode($results, true);
    //TODO get this not to open another page
    exit();
  } else {
    header("HTTP/1.0 400 not logged in");
    echo "error. not logged in";
  }
}

//Logs out the user and destorys the session variables
//stored by the login system
function logout(){
  global $root;
  $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
  $results = $apiClient->logout($_COOKIE['sid']);
  if($results['success'] == 1) {
    unset($_SESSION['username']);
    unset($_SESSION['isAdmin']);
    $_SESSION = array();
    session_regenerate_id(true);
    session_unset();
    session_destroy();
    setcookie('sid', '', time() - 3600);
    session_name('sid');
    session_start();
    //TODO get this not to open another page
    header("Location: http://$_SERVER[SERVER_NAME]");
    exit();
  } else {
    echo "error";
  }
}

//Changes the type of user in the database
function changeUser(){
  if(isset($_POST['user']) && isset($_POST['type']) && isAdmin() /*&& checkHeaders()*/){
    $user = $_POST['user'];
    $type = $_POST['type'];
    $user = mysql_real_escape_string($user);
    $dbconn = new dbconn("write");
    $dbconn->changeUser($user, $type);
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
    $dbconn = new dbconn("write");
    $dbconn->registerUser($personName, $username, $password, $email, $admin, $authyID);
  } else {
    echo 'nothing returned';
    header("HTTP/1.0 403 User Forbidden");
  }
}

//Changes the user's info
function changeUserInfo(){
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
      $dbconn = new dbconn("write");
      $result = $dbconn->updateUserInfo($username, $oldPassword, $newPassword, $confNewPassword, $authy, $card, $email, $name);
        //TODO this is the function name below
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

//Changes the password of the user based on the reset token and
//password
function forgotPassword(){
  if(isset($_GET['resetToken']) && isset($_POST['pass']) && isset($_POST['confirmPass']) ){
    $resetToken = $_GET['resetToken'];
    $pass = $_POST['pass'];
    $otherPass = $_POST['confirmPass'];
    $dbconn = new dbconn("write");
    $results = $dbconn->findResetToken($resetToken);
    if ($results && (strcmp($pass, $otherPass) == 0)){
      //resets the password....
      echo 'Found!';
      $dbconn = new dbconn("write");
      $userID = $dbconn->resetChangePassword($pass, $resetToken);
      $results = $dbconn->invalidateResetURL($resetToken, $userID);
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

//Creates a password reset URL for the given user
function resetPassword(){
  if (isset($_POST['username']) && isset($_POST['email'])){
    $username = $_POST['username'];
    $email = $_POST['email'];

    $dbconn = new dbconn("write");
    $dbconn->resetPassword($username, $email);

    echo 'Email sent to: ' . $email . '. Password for username: ' . $username . ' has been reset!';
    echo '<br> Please check your email';
    echo '<br> Click <a href="/">here</a> to go home';
  } else {
    header("Location: http://$_SERVER[HTTP_HOST]");
  }
}

?>
