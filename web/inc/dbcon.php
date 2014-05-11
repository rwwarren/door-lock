<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("mysqlUser.php");
//getting all the errors
ini_set('display_errors', 1);
 ini_set('log_errors', 1);
  error_reporting(E_ALL);

class dbconn {

  private $mysqli = null;

  //Queries the database for the username and password to login
  //the user on the site
  public function login($name, $password){
    include_once "variables.php";
    $password = passwordEncode($password);
    $stmt = $this->mysqli->prepare("SELECT ID, Name, Username, IsAdmin FROM Users WHERE Username=? and Password = PASSWORD(?) and IsActive = 1 LIMIT 1");
    $stmt->bind_param('ss', $name, $password);
    $stmt->execute();
    $stmt->bind_result($id, $name, $username, $isAdmin);
    $results = $stmt->fetch();
    if($results !== null){
      $userInfo = array();
      $userInfo['ID'] = $id;
      $userInfo['Name'] = $name;
      $userInfo['Username'] = $username;
      $userInfo['IsAdmin'] = $isAdmin;
      $stmt->free_result();
      $stmt->close();
      return $userInfo;
    } else {
      //echo '<br>Username or pwd incorrect';
      header("HTTP/1.0 403 Error Username or Password incorrect");
      header('Content-Type: application/json');
      echo json_encode(array('Invalid Username or Password' => $name, 'success' => '0' ));
      exit();
    }
  }

  //Queries the database for the authy token and username
  //returns true if it is found
  public function checkAuthy($name, $token){
    include_once "variables.php";
    $stmt = $this->mysqli->prepare("Select ID from Users where Username = ? and AuthyID = ? and IsActive = 1 LIMIT 1");
    $stmt->bind_param('ss', $name, $token);
    $stmt->execute();
    $stmt->bind_result($id);
    $results = $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    if($results !== null) {
      return true;
    } else {
      return false;
    }
  }

  //Closes the mysql database connection
  public function close(){
    if($this->mysqli === null) {
      die('Not Connected to a Database, can\'t disconnect');
    }
    $this->mysqli->close();
  }

  //Connects to the mysql database
  public function connect($user){
    $sqlUser = new users($user);
    $this->mysqli = new mysqli("localhost", $sqlUser->getUser(), $sqlUser->getPass(), "doorlock");
  }

  //Returnd the usernames from the database
  public function getUsers(){
    //selecting all the users
    $stmt = $this->mysqli->prepare("Select username from Users");
    $stmt->execute();
    $stmt->bind_result($username);
    $result = array();
    $i = 0;
    while ($stmt->fetch()) {
      $result[$i] = $username;
      $i++;
    }
    $stmt->free_result();
    $stmt->close();
    return $result;
  }

  //Returns the usernames of active users
  public function getActiveUsers(){
    $stmt = $this->mysqli->prepare("Select username from Users where IsAdmin = 0 and IsActive = 1");
    $stmt->execute();
    $stmt->bind_result($username);
    $result = array();
    $i = 0;
    while ($stmt->fetch()) {
      $result[$i] = $username;
      $i++;
    }
    $stmt->free_result();
    $stmt->close();
    return $result;
  }

  //Returns the usernames of inactive users
  public function getInactiveUsers(){
    $stmt = $this->mysqli->prepare("Select username from Users where IsActive = 0");
    $stmt->execute();
    $stmt->bind_result($username);
    $result = array();
    $i = 0;
    while ($stmt->fetch()) {
      $result[$i] = $username;
      $i++;
    }
    $stmt->free_result();
    $stmt->close();
    return $result;
  }

  //Returns the usernames of all the admins
  public function getAdmins(){
    $stmt = $this->mysqli->prepare("Select username from Users where IsAdmin = 1 and IsActive = 1");
    $stmt->execute();
    $stmt->bind_result($username);
    $result = array();
    $i = 0;
    while ($stmt->fetch()) {
      $result[$i] = $username;
      $i++;
    }
    $stmt->free_result();
    $stmt->close();
    return $result;
  }

  //Changes the username's password if the username and password
  //are valid
  public function changePassword($user, $oldPass, $newPass){
    include_once "variables.php";
    $oldPass = passwordEncode($oldPass);
    $stmt = $this->mysqli->prepare("Select ID from Users where Username = ? and Password = PASSWORD(?)");
    $stmt->bind_param('ss', $user, $oldPass);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    //Change so that it only finds the one
    if($result !== null){
      //change the pwd
      $newPass = passwordEncode($newPass);
      //$stmt->reset();
      $stmt2 = $this->mysqli->prepare("UPDATE Users SET Password=PASSWORD(?) WHERE Username= ? AND Password=PASSWORD(?)");
      $stmt2->bind_param('sss', $newPass, $user, $oldPass);
      $stmt2->execute();
      $stmt2->free_result();
      $stmt2->close();
      return 200;
    } else {
      //$stmt->close();
      echo 'not changed!';
      header("HTTP/1.0 401 Password Incorrect");
    }
  }

  //Updates the user's information
  public function updateUserInfo($username, $oldPassword, $newPassword = null, $authy = null, $card = null, $email = null, $name = null ){
    if($username === null) {

    } else {
      //$stmt->close();
      echo 'not changed!';
      header("HTTP/1.0 401 Password Incorrect");
    }
  }

  //Decide if this is the route I want to go
  public function checkPassword($username, $password){
    //
  }

  //Finds the resetURL from the database and changes the password
  //if there is a ResetURL
  public function resetChangePassword($newPass, $token){
    include_once "variables.php";
    $stmt = $this->mysqli->prepare("Select UserID from ResetURLs where ResetURL = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->bind_result($userID);
    $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    if($userID !== null) {
      $newPass = passwordEncode($newPass);
      $stmt = $this->mysqli->prepare("UPDATE Users SET Password=PASSWORD(?) WHERE ID= ?");
      $stmt->bind_param('ss', $newPass, $userID);
      $stmt->execute();
      $stmt->free_result();
      $stmt->close();
      return $userID;
    } else {
      echo 'not changed!';
      header("HTTP/1.0 401 Password Incorrect");
    }
  }

  //Resgisters the user with a name, username, email, and authyID
  public function registerUser($personName, $username, $password, $email, $admin, $authyID){
    //test if there already is that user
    $stmt = $this->mysqli->prepare("Select ID from Users where Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $results = $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    if($results === null) {
      include_once "variables.php";
      $password = passwordEncode($password);
      $stmt2 = $this->mysqli->prepare("INSERT INTO Users VALUES(DEFAULT, ?, ?, PASSWORD(?), ?, ?, DEFAULT, ?, DEFAULT, DEFAULT)");
      if (strlen($authyID) === 0 || !is_numeric($authyID)){
        $authyID = " DEFAULT";
      }
      $stmt2->bind_param('ssssss', $personName, $username, $password, $email, $admin, $authyID);
      $stmt2->execute();
      $stmt2->free_result();
      $stmt2->close();
      echo 'Added user: ' . $personName;
    } else {
      return 'User is already a part of the system';
    }
  }

  //"Removes" the user by setting them to inactive
  public function removeUser($user){
    $stmt = $this->mysqli->prepare("UPDATE Users SET IsActive = 0 WHERE Username = ?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $stmt->free_result();
    $stmt->close();
  }

  //Changes what type the user is
  //like admin, user active, user inactive
  public function changeUser($user, $type){
    if ($type == 'admin'){
      $query = "UPDATE Users SET IsActive = 1, IsAdmin = 1 WHERE Username = ?";
    } else if ($type == 'active'){
      $query = "UPDATE Users SET IsActive = 1, IsAdmin = 0 WHERE Username = ?";
    } else if($type == 'inactive'){
      $query = "UPDATE Users SET IsActive = 0, IsAdmin = 0 WHERE Username = ? ";
    } else {
      echo 'Type is invalid';
      header("HTTP/1.0 400 Bad Request");
      die();
    }
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $stmt->free_result();
    $stmt->close();
  }

  //Sends the user an email with a link to reset
  //their password
  public function resetPassword($username, $email){
    //TODO sends the user an email and resets their password
    //make a mysql table that has : userId, passLink, vaild until, isUsed
    include_once 'extraFunctions.php';
    include_once 'variables.php';
    $userInfo = $this->getUserInfo($username);//get user ID somehow.....
    $userID = $userInfo['ID'];
    $user = $userInfo['Name'];
    $newPassword = createTempPassword($username);
    $found = $this->findUser($username, $email);
    if ($found){
      $this->resetPassQuery($userID, $newPassword, true);
      //
      //TODO: make sure the time is valid and isValid
      sendMail($user, $email, $newPassword);
    }
  }

  //Checks if there is a reset token that is valid
  public function findResetToken($resetToken){
    $stmt = $this->mysqli->prepare("Select UserID From ResetURLs WHERE ResetURL = ? AND isValid = 1 AND Expiration >= CURRENT_TIMESTAMP");
    $stmt->bind_param('s', $resetToken);
    $stmt->execute();
    $stmt->bind_result($token);
    $results = $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    if($results !== null){
      return true;
    } else {
      return false;
    }
  }

  //Checks if there is a user with the passed in username and email
  private function findUser($username, $email){
    $stmt = $this->mysqli->prepare("Select ID from Users where Username = ? and Email = ? and IsActive = 1");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $stmt->bind_result($userID);
    $results = $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    if($results !== null){
      return true;
    } else {
      return false;
    }
  }

  //Adds a reset url into the database
  private function resetPassQuery($userID, $newPassword, $isValid){
    if ($isValid){
      $stmt = $this->mysqli->prepare("Select ResetURL From ResetURLs WHERE UserID = ? AND isValid = 1");
      $stmt->bind_param('s', $userID);
      $stmt->execute();
      $stmt->bind_result($resetToken);
      while($stmt->fetch()){
        $this->invalidateResetURL($resetToken, $userID);
      }
      $stmt->free_result();
      $stmt->close();
      $expireTime = date('Y-m-d H:i:s', strtotime("+2 day", time()));
      $stmt = $this->mysqli->prepare("INSERT INTO ResetURLs VALUES(DEFAULT, ?, ?, ?, DEFAULT)");
      $stmt->bind_param('sss', $userID, $newPassword, $expireTime);
      $stmt->execute();
      $stmt->fetch();
      $stmt->free_result();
      $stmt->close();
    } else if (!$isValid){
      include_once "variables.php";
      //TODO how do i pull this reset token??
      $this->invalidateResetURL($resetToken, $userID);
    } else {
      die('invalid parameter');
    }
  }

  //Invalidates the reset token
  public function invalidateResetURL($resetToken, $userID){
    $stmt = $this->mysqli->prepare("UPDATE ResetURLs SET isValid = 0 WHERE ResetURL = ? AND UserID = ?");
    $stmt->bind_param('ss', $resetToken, $userID);
    $stmt->execute();
    $stmt->free_result();
    $stmt->close();
  }

  //Returns ID, eail, and name from a username
//  private function getUserInfo($username){
  public function getUserInfo($username){
//    $stmt = $this->mysqli->prepare("Select ID, Email, Name from Users where username = ? and IsActive = 1");
    $stmt = $this->mysqli->prepare("Select ID, Email, Name, CardID, AuthyID from Users where username = ? and IsActive = 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($userID, $email, $name, $CardID, $AuthyID);
//    $stmt->bind_result($userID, $email, $name);
    $results = $stmt->fetch();
    $stmt->free_result();
    $stmt->close();
    if($results === null) {
      die("no user exists");
    }
    $AuthyID = ($AuthyID === 0) ? NULL : $AuthyID;
//    return array('ID' => $userID, 'Email' => $email, 'Name' => $name);
    return array('ID' => $userID, 'Email' => $email, 'Name' => $name, 'CardID' => $CardID, 'AuthyID' => $AuthyID);
  }

}

?>
