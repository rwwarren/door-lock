<?php

class Users {

  private $dbhost;
  private $dbname;
  private $dbusername;
  private $dbpassword;

  function __construct($type){
    $config = parse_ini_file("../properties/secure.ini");
    switch($type) {
      case "read":
        $this->dbusername = $config['db.read.username'];
        $this->dbpassword = $config['db.read.password'];
        break;
      case "write":
        $this->dbusername = $config['db.write.username'];
        $this->dbpassword = $config['db.write.password'];
        break;
      default:
        //die('Database user not selected properly');
        //error_log("Tried to connect to DB without user and used type: " . $type);
        throw new InvalidArgumentException('Database user not selected properly');
        break;
    }
    $this->dbhost = $config['db.host'];
    $this->dbname = $config['db.name'];
    if($this->dbhost === null || $this->dbname === null || $this->dbusername === null || $this->dbpassword == null){
      throw new InvalidArgumentException('Someone in mysqlUser is null which is illegal');
    }
  }

  function getHost(){
    return $this->dbhost;
  }

  function getDatabase(){
    return $this->dbname;
  }

  function getUser(){
    return $this->dbusername;
  }

  function getPass(){
    return $this->dbpassword;
  }
}

?>
