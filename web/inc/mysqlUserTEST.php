<?php

class Users {

  private $dbusername;
  private $dbpassword;

  function __construct($type){
    switch($type) {
      case "read":
        $this->dbusername = 'read';
        $this->dbpassword = 'PASSWORD';
        break;
      case "write":
        $this->dbusername = 'write';
        $this->dbpassword = 'PASSWORD';
        break;
      default:
        die('Database user not selected properly');
        error_log("Tried to connect to DB without user and used type: " . $type);
        break;
    }
  }

  function getUser(){
    return $this->dbusername;
  }

  function getPass(){
    return $this->dbpassword;
  }
}

?>
