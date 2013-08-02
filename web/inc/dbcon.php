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

  protected $query = null;

  public function login($name, $password){
    $query = "Select * from web_users where name = \"" . $name . "\" and pass = PASSWORD(\"" . $password . "\") ";

  }

  public function close(){
    if($this->conn === null) {
      die('Not Connected to a Datbase');
    }
    mysql_close($this->conn);
  }

  public function connect($user){
    //mysql_info();
    //print_r( get_loaded_extensions() ) ;  
    $sqlUser = new users($user);
    echo '<br>';
    //echo $sqlUser->getUser();
    echo '<br>';
    //echo $sqlUser->getPass();
    $conn = mysql_connect("localhost", $sqlUser->getUser(), $sqlUser->getPass()) or die ("Could not connect to the database");
    //mysql_error();
    echo "connected <br>";
    $selected = mysql_select_db("reader",$conn) 
      or die("Could not select examples");
    $result = mysql_query("SELECT * from web_users;");
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      //print_r($row);
    }
    echo "<br>Connected to MySQL<br>";
    //echo $conn;
    //echo 'asdf';

  }

  public function changePassword($user, $oldpass, $newPWD){
  
  }

}

?>
