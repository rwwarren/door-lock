<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("mysqlUser.php");
//getting all the errors
ini_set('display_errors', 1);
 ini_set('log_errors', 1);
  error_reporting(E_ALL);


class dbconn {
  
  private $conn = null;

  public function login($name, $password){
    include_once "variables.php";
    $query = "Select * from Users where Username = \"" . $name . "\" and Password = PASSWORD(\"" . passwordEncode($password) . "\") and IsActive = 1 ";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    $row = mysql_fetch_row($results, MYSQL_ASSOC);
    if(sizeof($row) > 1) {
      echo 'Not null';
      $_SESSION['name'] = $row['Name'];
      $_SESSION['username'] = $row['Username'];
      $_SESSION['userID'] = $row['ID'];
      //TODO an admin table or something
      //TODO left join? or just keep in the same table
      $_SESSION['isAdmin'] = $row['IsAdmin'];
      session_write_close();
      header("HTTP/1.0 200 Success");
      return true; 
    } else {
      echo '<br>Username or pwd incorrect';
      header("HTTP/1.0 403 Error Username or Password incorrect");
      exit();
    }
  }

  public function checkAuthy($name, $token){
    include_once "variables.php";
    $query = "Select * from Users where Username = \"" . $name . "\" and AuthyID = \"" . $token . "\" and IsActive = 1 ";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    $row = mysql_fetch_row($results, MYSQL_ASSOC);
    if(sizeof($row) > 1) {
      return true;
    } else {
      return false;
    }
  }

  public function close(){
    if($this->conn === null) {
      die('Not Connected to a Database, can\'t disconnect');
    }
    mysql_close($this->conn);
  }

  public function connect($user){
    $sqlUser = new users($user);
    $this->conn = mysql_connect("localhost", $sqlUser->getUser(), $sqlUser->getPass()) or die ("Could not connect to the database");
    $selected = mysql_select_db("doorlock",$this->conn) 
      or die("Could not select a database");
  }

  public function getUsers(){
    //selecting all the users
    //$query = "Select * from Users";
    $query = "Select username from Users";
    $results = mysql_query($query, $this->conn);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize; $i++){
      $result[$i] = mysql_result($results, $i);
    }
    return $result;
  }


    //TODO change so the 3 functions are the same
    //but with different queries
  public function getActiveUsers(){
    $query = "Select username from Users where IsAdmin = 0 and IsActive = 1;";
    $results = mysql_query($query, $this->conn);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize; $i++){
      $result[$i] = mysql_result($results, $i);
    }
    return $result;

  }


  public function getInactiveUsers(){
    //selecting all the users
    $query = "Select username from Users where IsActive = 0;";
    $results = mysql_query($query, $this->conn);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize; $i++){
      $result[$i] = mysql_result($results, $i);
    }
    return $result;

  }


  public function getAdmins(){
    $query = "Select username from Users where IsAdmin = 1 and IsActive = 1;";
    $results = mysql_query($query, $this->conn);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize; $i++){
      $result[$i] = mysql_result($results, $i);
    }
    return $result;

  }

  public function changePassword($user, $oldPass, $newPass){
    include_once "variables.php";
    $query = "Select * from Users where Username = \"" . $user . "\" and Password = PASSWORD(\"" . passwordEncode($oldPass) . "\") ";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    $row = mysql_fetch_row($results, MYSQL_ASSOC);
    //Change so that it only finds the one
    if (sizeof($row) > 1) {
      //change the pwd
      $query = "UPDATE Users SET Password=PASSWORD(\"" . passwordEncode($newPass) . "\") WHERE Username=\"" . $user . "\" AND Password=PASSWORD(\"" . passwordEncode($oldPass) . "\");";
      $results = mysql_query($query, $this->conn);
      return 200;
    } else {
      echo 'not changed!';
      header("HTTP/1.0 401 Password Incorrect");
    }
  }

  public function registerUser($personName, $username, $password, $email, $admin){
    //test if there already is that user
    
    $query = "Select name from Users where username = \"" . $username . "\";";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    $rows = mysql_num_rows($results);
    if ($rows < 1) {
      include_once "variables.php";
      $query = "INSERT INTO Users VALUES(DEFAULT,\"" . $personName . "\", \"" . $username . "\", PASSWORD(\"" . passwordEncode($password) . "\"), \"" . $email . "\", DEFAULT, DEFAULT, \"". $admin . "\", DEFAULT, DEFAULT);";
      $query = stripslashes($query);
      $results = mysql_query($query, $this->conn);
      echo 'Added user: ' . $personName;
    } else {
      return 'User is already a part of the system';
    }
  }

  public function removeUser($user){
    $query = "UPDATE Users SET IsActive = 0 WHERE Username = \"" . $user . "\";";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
  }

  //changes what type the user is
  //like admin, user active, user inactive
  public function changeUser($user, $type){
    if ($type == 'admin'){
      $query = "UPDATE Users SET IsActive = 1, IsAdmin = 1 WHERE Username = \"" . $user . "\";";
    } else if ($type == 'active'){
      $query = "UPDATE Users SET IsActive = 1, IsAdmin = 0 WHERE Username = \"" . $user . "\";";
    } else if($type == 'inactive'){
      $query = "UPDATE Users SET IsActive = 0, IsAdmin = 0 WHERE Username = \"" . $user . "\";";
    } else {
      echo 'Type is invalid';
      header("HTTP/1.0 400 Bad Request");
      die();
    }
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
  }

  //TODO have reset create the new one from the url
  //have forgot create temp url
  public function resetPassword($username){
    //TODO sends the user an email and resets their password
    //make a mysql table that has : userId, passLink, vaild until, isUsed
    include_once 'extraFunctions.php';
    include_once 'variables.php';
    //TODO
    //some db shit
    $userInfo = $this->getUserInfo($username);//get user ID somehow.....
    //print_r($userInfo);
    $userID = $userInfo['ID'];
    $user = $userInfo['Name'];
    $newPassword = createTempPassword($username);
    //echo $newPassword;
    $this->resetPassQuery($userID, $newPassword, true);
    //
    //TODO: make sure the time is valid and isValid
    //sendMail($user, $sendEmail, $newPassword);
  }

  public function updateResetPassword($resetToken){
    //
    //$query = 'Select * From ResetURLs WHERE ResetURL = \'' . $resetToken .'\' AND isValid = 1 AND Expiration  \'' . $timenow . '\' >= CURRENT_TIMESTAMP;';
    $query = 'Select * From ResetURLs WHERE ResetURL = \'' . $resetToken .'\' AND isValid = 1 AND Expiration >= CURRENT_TIMESTAMP;';
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    $row = mysql_fetch_row($results, MYSQL_ASSOC);
    //print_r($row);
    if (sizeof($row) > 1) {
      return true;
    } else {
      return false;
    }
    //echo mysql_errno($this->conn) .':' . mysql_error($this->conn);
  }

  private function resetPassQuery($userID, $newPassword, $isValid){
    //
    if ($isValid){
      //$query = 'INSERT INTO ResetURLs VALUES(DEFAULT,  "' . $userID . '", "' . $newPassword . '", DEFAULT, DEFAULT);';
      //TODO check if there are other reset password tokens
      $precheckQuery = 'Select * From ResetURLs WHERE UserID = \'' . $userID .'\' AND isValid = 1;';
      $precheckQuery = stripslashes($precheckQuery);
      $results = mysql_query($precheckQuery, $this->conn);
      while ($rows = mysql_fetch_row($results, MYSQL_ASSOC)){
        print_r($rows);
        $resetToken = $rows['ResetURL'];
        echo $resetToken;
        $this->invalidateResetURL($resetToken, $userID);
        echo '<br>';
      }

      $expireTime = date('Y-m-d H:i:s', strtotime("+2 day", time()));
      $query = 'INSERT INTO ResetURLs VALUES(DEFAULT,  "' . $userID . '", "' . $newPassword . '", " ' . $expireTime .'", DEFAULT);';
      $query = stripslashes($query);
      $results = mysql_query($query, $this->conn);
    } else if (!$isValid){
      //$query = 'UDPATE ResetURLs SET isValid = 0 WHERE UserId = \'' . $userID . '\';';
      include_once "variables.php";
      //TODO how do i pull this reset token??
      $this->invalidateResetURL($resetToken, $userID);
      //$query = 'UDPATE ResetURLs SET isValid = 0 WHERE resetPassURL = \'' . $resetToken . '\' AND UserID = \'' . $UserID . '\';';
      //TODO also
      //$query = 'UDPATE Users SET password = \'' . passwordEncode($newPassword). '\' WHERE ID = \'' . $userID . '\';';
    } else {
      die('invalid parameter');
    }
    echo '<br>';
    echo 'complete';
  }

  private function invalidateResetURL($resetToken, $userID){
    //
    $query = 'UPDATE ResetURLs SET isValid = 0 WHERE ResetURL = \'' . $resetToken . '\' AND UserID = \'' . $userID . '\';';
    $query = stripslashes($query);
    echo '<br>';
    echo $query;
    echo '<br>';
    //echo mysql_errno($this->conn) .':' . mysql_error($this->conn);
    $results = mysql_query($query, $this->conn);
  }

  private function getUserInfo($username){
    $query = "Select ID, Email, Name from Users where username = '$username' and IsActive = 1;";
    $results = mysql_query($query, $this->conn);
    $row = mysql_fetch_row($results, MYSQL_ASSOC);
    //$userID = $row['ID'];
    $userInfo = $row;
    if (sizeof($row) < 1){
      die("no user exists");
    }
    return $userInfo;
  }


}

?>
