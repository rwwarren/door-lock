<?php
//dbconnection
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/mysqlUsers.php");
//getting all the errors
ini_set('display_errors', 1);
 ini_set('log_errors', 1);
 ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
  error_reporting(E_ALL);


class dbconn {
  
  private $conn = null;

  //protected $query = null;

  public function login($name, $password){
    $query = "Select * from web_users where name = \"" . $name . "\" and pass = PASSWORD(\"" . $password . "\") and user_is_current = 1 ";
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
      session_start();
      setcookie("sid", session_id(), time()+3600);
      setcookie("n", $name, time()+3600);
      $update = "UPDATE web_users SET session_id=\"" . session_id() . "\", user_session_valid=1, session_expire=now()+3600 WHERE name=\"" . $name . "\";";
      mysql_query($update, $this->conn);
      //TODO somehow loook at the session and valid and expire later
    } else {
      echo 'null sad face';
      echo '<br>Username or pwd incorrect';
      //TODO change this
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
    $selected = mysql_select_db("reader",$this->conn) 
      or die("Could not select examples");
    /*$result = mysql_query("SELECT * from web_users;");
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
    //$query = "Select * from web_users";
    $query = "Select name from web_users";
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

    $query = "Select * from web_users where name = \"" . $name . "\" and pass = PASSWORD(\"" . $oldPass . "\") ";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    //Change so that it only finds the one
    if (sizeof($results) > 1) {
      //change the pwd
      $query = "UPDATE web_users SET pass=PASSWORD(\"" . $newPass . "\") WHERE name=\"" . $user . "\" AND pass=PASSWORD(\"" . $oldPass . "\");";
      //say that the pwd was now changed or not
    } else {
      echo 'not changed!';
    }
  }

  public function registerUser($newUser, $newPass){
    //test if there already is that user
    $query = "Select * from web_users where name = \"" . $name . "\"";
    $query = stripslashes($query);
    $results = mysql_query($query, $this->conn);
    if (sizeof($results) < 1) {
      $query = "INSERT INTO web_users VALUES(\"\",\"" . $newUser . "\", PASSWORD(\"" . $newPass . "\"), \"none\", 1, 1, CURRENT_TIMESTAMP(), DEFAULT, 0);";
      $query = stripslashes($query);
      $results = mysql_query($query, $this->conn);
      echo 'Added user: ' . $newUser;
    } else {
      return 'User is already a part of the system';
    }
  }

  function removeUser($user){
    //change isCurrent to 0
  }

}

?>
