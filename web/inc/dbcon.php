<?php
//dbconnection
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once("$root/../inc/mysqlUsers.php");
require_once("mysqlUser.php");
//getting all the errors
ini_set('display_errors', 1);
 ini_set('log_errors', 1);
  error_reporting(E_ALL);


class dbconn {
  
  private $conn = null;

  //protected $query = null;

  public function login($name, $password){
    //TODO salt the password
    include_once "variables.php";
    $query = "Select * from Users where Username = \"" . $name . "\" and Password = PASSWORD(\"" . passwordEncode($password) . "\") and IsActive = 1 ";
    $query = stripslashes($query);
    //echo $query;
    //$conn = $this->conn;
    //$results = mysql_fetch_array($query, MYSQL_ASSOC)
    $results = mysql_query($query, $this->conn);
    $row = mysql_fetch_row($results, MYSQL_ASSOC);
    //echo sizeof($row);
    if(sizeof($row) > 1) {
      echo 'Not null';
      //print_r($row);
      //session_start();
      //$_SESSION['isLoggedIn'] = 1;
      $_SESSION['userName'] = $row['Name'];//$name;
      //TODO an admin table or something
      //TODO left join? or just keep in the same table
      $_SESSION['isAdmin'] = $row['IsAdmin'];//$name;
      session_write_close();
      //TODO check Authy here somewhere
      //setcookie("sid", session_id(), time()+3600);
      //setcookie("n", $name, time()+3600);
      //$update = "UPDATE Users SET session_id=\"" . session_id() . "\", user_session_valid=1, session_expire=now()+3600 WHERE name=\"" . $name . "\";";
      //mysql_query($update, $this->conn);
      header("HTTP/1.0 200 Success");
      return true; 
      //TODO somehow loook at the session and valid and expire later
    } else {
      echo $name . ' and ' . $password;
      echo 'null sad face';
      echo '<br>Username or pwd incorrect';
      //TODO change this
      header("HTTP/1.0 403 Error Username or Password incorrect");
      exit();
    }
    //print_r($row);
  }

  public function close(){
    if($this->conn === null) {
      die('Not Connected to a Database, can\'t disconnect');
    }
    mysql_close($this->conn);
  }

  public function connect($user){
    //mysql_info();
    //print_r( get_loaded_extensions() ) ;  
    $sqlUser = new users($user);
    //echo '<br>';
    //echo $sqlUser->getUser();
    //echo '<br>';
    //echo $sqlUser->getPass();
    $this->conn = mysql_connect("localhost", $sqlUser->getUser(), $sqlUser->getPass()) or die ("Could not connect to the database");
    //mysql_error();
    //echo "connected <br>";
    $selected = mysql_select_db("doorlock",$this->conn) 
      or die("Could not select examples");
    /*$result = mysql_query("SELECT * from Users;");
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      //print_r($row);
    }
    echo "<br>Connected to MySQL<br>";
     */
    //echo $conn;
    //echo 'asdf';

  }

  public function getUsers(){
    //selecting all the users
    //$query = "Select * from Users";
    $query = "Select name from Users";
    $results = mysql_query($query, $this->conn);
    //$results = mysql_result($query, $this->conn);
    //$result = mysql_result($results, 0);
    //$row = mysql_result($results);
    //print_r($row);
    //$row = mysql_fetch_assoc($results);
    //$row = mysql_fetch_array($results, MYSQL_NUM);
    
    //$result = array();
    //$i = 0;
    //while ($row = mysql_fetch_assoc($results)){
    //print_r($row);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize/*sizeof($results)*/; $i++){
      $result[$i] = mysql_result($results, $i);//$row['name'];
      //$i++;
    }
    
    //return $result;
    
    //return $row;
    return $result;

  }


    //TODO change so the 3 functions are the same
    //but with different queries
  public function getActiveUsers(){
    //selecting all the users
    //$query = "Select * from Users";
    $query = "Select name from Users where IsAdmin = 0 and IsActive = 1;";
    $results = mysql_query($query, $this->conn);
    //$results = mysql_result($query, $this->conn);
    //$result = mysql_result($results, 0);
    //$row = mysql_result($results);
    //print_r($row);
    //$row = mysql_fetch_assoc($results);
    //$row = mysql_fetch_array($results, MYSQL_NUM);
    
    //$result = array();
    //$i = 0;
    //while ($row = mysql_fetch_assoc($results)){
    //print_r($row);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize/*sizeof($results)*/; $i++){
      $result[$i] = mysql_result($results, $i);//$row['name'];
      //$i++;
    }
    
    //return $result;
    
    //return $row;
    return $result;

  }


  public function getInactiveUsers(){
    //selecting all the users
    //$query = "Select * from Users";
    $query = "Select name from Users where IsActive = 0;";
    $results = mysql_query($query, $this->conn);
    //$results = mysql_result($query, $this->conn);
    //$result = mysql_result($results, 0);
    //$row = mysql_result($results);
    //print_r($row);
    //$row = mysql_fetch_assoc($results);
    //$row = mysql_fetch_array($results, MYSQL_NUM);
    
    //$result = array();
    //$i = 0;
    //while ($row = mysql_fetch_assoc($results)){
    //print_r($row);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize/*sizeof($results)*/; $i++){
      $result[$i] = mysql_result($results, $i);//$row['name'];
      //$i++;
    }
    
    //return $result;
    
    //return $row;
    return $result;

  }


  public function getAdmins(){
    //selecting all the users
    //$query = "Select * from Users";
    $query = "Select name from Users where IsAdmin = 1 and IsActive = 1;";
    $results = mysql_query($query, $this->conn);
    //$results = mysql_result($query, $this->conn);
    //$result = mysql_result($results, 0);
    //$row = mysql_result($results);
    //print_r($row);
    //$row = mysql_fetch_assoc($results);
    //$row = mysql_fetch_array($results, MYSQL_NUM);
    
    //$result = array();
    //$i = 0;
    //while ($row = mysql_fetch_assoc($results)){
    //print_r($row);
    $theSize = mysql_num_rows($results);
    $result = array();
    for($i = 0; $i < $theSize/*sizeof($results)*/; $i++){
      $result[$i] = mysql_result($results, $i);//$row['name'];
      //$i++;
    }
    
    //return $result;
    
    //return $row;
    return $result;

  }

  public function changePassword($user, $oldPass, $newPass){

    include_once "variables.php";
    $query = "Select * from Users where Name = \"" . $name . "\" and Password = PASSWORD(\"" . passwordEncode($oldPass) . "\") ";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    //Change so that it only finds the one
    if (sizeof($results) > 1) {
      //change the pwd
      $query = "UPDATE Users SET Password=PASSWORD(\"" . passwordEncode($newPass) . "\") WHERE Name=\"" . $user . "\" AND Password=PASSWORD(\"" . passwordEncode($oldPass) . "\");";
      //say that the pwd was now changed or not
    } else {
      echo 'not changed!';
    }
  }

  public function registerUser($personName, $username, $password, $email){
    //test if there already is that user
    
    $query = "Select name from Users where username = \"" . $username . "\";";
    $query = stripslashes($query);
    echo $query;
    $results = mysql_query($query, $this->conn);
    mysql_error();
    echo "<br>";
    //$row = mysql_fetch_assoc($results);
    //$row = mysql_fetch_row($results, MYSQL_ASSOC);
    //$results = mysql_query($query, $this->conn);
    //$row = mysql_fetch_row($results, MYSQL_ASSOC);
    $rows = mysql_num_rows($results);
    if ($rows < 1) {
      include_once "variables.php";
      $query = "INSERT INTO Users VALUES(DEFAULT,\"" . $personName . "\", \"" . $username . "\", PASSWORD(\"" . passwordEncode($password) . "\"), \"" . $email . "\", DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT);";
      echo $query;
      $query = stripslashes($query);
      $results = mysql_query($query, $this->conn);
      echo 'Added user: ' . $personName;
    } else {
      print_r($results);
    echo "<br>";
      print_r(sizeof($results));
    echo "<br>";
      return 'User is already a part of the system';
    }
  }

  public function removeUser($user){
    //change isCurrent to 0
    $query = "UPDATE Users SET IsActive = 0 WHERE Username = \"" . $user . "\";";
    $query = stripslashes($query);
    echo $query;
    $results = mysql_query($query, $this->conn);
  }

  //changes what type the user is
  //like admin, user active, user inactive
  public function changeUser($user, $type){
    //TODO 3 different types
  }

  public function resetPassword($user){
    //TODO sends the user an email and resets their password
    //make a mysql table that has : userId, passLink, vaild until, isUsed
    include_once 'extraFunctions.php';
    $user .= '';
    $newPassword = createTempPassword($user);
    sendMail($name, $sendEmail, $newPassword);
  }

  public function createTempPassword($user){
    //
    return $newPassword;
  }

}

?>
