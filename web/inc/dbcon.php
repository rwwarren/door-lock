<?php
//dbconnection
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("mysqlUsers.php");

class dbconn {
  
  private $conn = null;

  protected $query = null;

  public function login($name, $password){

  }

  public function close(){
    if($this->conn === null) {
      die('Not Connected to a Datbase');
    }
    mysql_close($this->conn);
  }

  public function connect($user, $pass){

  }

  public function changePassword($user, $oldpass, $newPWD){
  
  }

}

?>
