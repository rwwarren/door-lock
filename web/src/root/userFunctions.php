<?php

ini_set("session.hash_function", "sha512");
session_name('sid');
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
//require '../includedPackages/authy-php/Authy.php';
//require_once('Authy/Authy.php');
require_once("$root/../vendor/autoload.php");
//require_once("$root/../vendor/autoload.php");
require_once("$root/../inc/variables.php");
require_once("$root/../inc/extraFunctions.php");
include_once($root . "/../vendor/door-lock/api-client/src/root/apiClient.php");


if (isset($_GET['actions']) && (strpos($_SERVER["REQUEST_URI"], 'userFunctions.php') === false)){
  //TODO make sure that request comes from localhost
  $type = $_GET['actions'];
  if ($type == 'login'){
    login();
  } else if ($type == 'logout' && isLoggedIn()){
    logout();
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
  if(isset($_POST['Username']) && isset($_POST['Password']) /*&& checkHeaders()*/ && isset($_POST['Token'])){
    $user = $_POST['Username'];
    $pass = $_POST['Password'];
    $token = $_POST['Token'];
    global $root;

    //
//    $root = realpath(dirname(__FILE__));
//    include_once($root . "/../../../../door-lock-api-client/src/root/apiClient.php");
//    include_once($root . "/../../../api-client/src/root/apiClient.php");
//    include_once("/Users/ryan/Documents/door-lock/api-client/src/root/apiClient.php");
    $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
    $results = $apiClient->login($_POST, $_COOKIE['sid']);
//    print_r($results);

//    echo "This is a success";
//    echo "<br>";
//    print_r(session_id());
//    print_r(session_get_cookie_params());
//    print_r($results);
    if($results !== null){
      $_SESSION['username'] = $_POST['Username'];
//      print_r($results);
//      $decoded = json_encode($results);
//      $intermediate = json_decode($results);
//      $decoded = json_decode($intermediate, true);
      //HERE
//      print_r($decoded);
//      echo "sadf: " . $decoded['name'] . "sdf <br>";

//      $test = json_encode('{"username":"test","name":"Test","success":"1"}');
////      print_r($test);
//      $newone = json_decode($test);
//      $newone = json_decode($newone, true);
////      $newone = zend_json($test);
////      $newone = json_decode(html_entity_decode($test, ENT_QUOTES), true);
//      print_r($newone);
////      echo "class: " . get_class($newone);
////      echo "test: " . $newone->{'name'};
//      echo "test: " . $newone['name'];
////      $_SESSION['name'] = $decoded['name'];

//      $_SESSION['name'] = $decoded['name'];
//      $_SESSION['username'] = $decoded['username'];
//      $_SESSION['isAdmin'] = $decoded['isAdmin'];
      $_SESSION['name'] = $results['name'];
      $_SESSION['username'] = $results['username'];
      $_SESSION['isAdmin'] = $results['isAdmin'];

//      $_SESSION['username'] = $results['username'];
//      $_SESSION['userID'] = $userInfo['ID'];
//      $_SESSION['isAdmin'] = $userInfo['IsAdmin'];
//      session_write_close();
//      exit();
    }
//    exit();

    //
//    $dbconn = new dbconn("read");
    //$user = mysqli_real_escape_string($dbconn, $user);
    //$pass = mysqli_real_escape_string($dbconn, $pass);
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
    //$verification = $authy_api->verifyToken(234, "$token");
    //$authy_api = new Authy_Api('#your_api_key', 'http://sandbox-api.authy.com');
    //if($authyValid && $verification->ok()){
    //TODO above commented out to save testing hassle
    //$_SESSION['username'] = 'asdf';
//    $test = true;
//    $test = false;
//    if($test == true){
//      echo '<br> authy token is okay';
//      //$dbconn->connect("read");
//      //$dbconn->login($user, $pass);
//      //$_SESSION['username'] = 'asdf';
//      $userInfo = array();
//      $userInfo = $dbconn->login($user, $pass);
//      //if($userInfo !== NULL){
//      if($userInfo !== false){
//        $_SESSION['name'] = $userInfo['Name'];
//        $_SESSION['username'] = $userInfo['Username'];
//        $_SESSION['userID'] = $userInfo['ID'];
//        $_SESSION['isAdmin'] = $userInfo['IsAdmin'];
//        session_write_close();
////      } else if($test === true){
//      } else {
//        header("HTTP/1.0 403 Error Username or Password incorrect");
//        header('Content-Type: application/json');
//        echo json_encode(array('Invalid Username or Password' => $user, 'success' => '0' ));
//      }
//
//      //}
//      //$dbconn->close();
//    } else { //authy is not right
//      header("HTTP/1.0 401 Authy key wrong");
//      exit();
//    }
  } else {
    echo "nope";
    echo '<br>No username or password entered';
//    print_r($_POST);
    header("HTTP/1.0 400 Username or password not entered");
    exit();
  }
}

//Logs out the user and destorys the session variables
//stored by the login system
function logout(){
//  $root = realpath(dirname(__FILE__));
//  include_once($root . "/../../../../door-lock-api-client/src/root/apiClient.php");
  global $root;
  $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
  $results = $apiClient->logout($_COOKIE['sid']);
//  $intermediate = json_decode($results, true);
//  $intermediate = json_decode($results);
//  $decoded = $intermediate;
//  $decoded = json_decode($intermediate, true);
//  print_r($decoded);
  if($results['success'] == 1) {
//  if($decoded['success'] == 1) {
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
    //header("Location: http://$_SERVER[HTTP_HOST]");
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
    //$dbconn->connect("write");
    $dbconn->changeUser($user, $type);
    //$dbconn->close();
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
    //$dbconn->connect("write");
    $dbconn->registerUser($personName, $username, $password, $email, $admin, $authyID);
    //$dbconn->close();
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

//Changes the password of the user based on the reset token and
//password
function forgotPassword(){
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

//Creates a password reset URL for the given user
function resetPassword(){
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

?>
