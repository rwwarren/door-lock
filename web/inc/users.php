<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require_once("$root/../inc/template.php");

class userEdit extends Page{

  //$dbconn = new dbconn;

  public function getHeader(){
    return 'PiDuinoLock';
  }

  public function getBody(){
    return //"You are logged in";
      'You are logged in <a href="/logout.php">Log out</a>' .
        '<br>' .
        'Wooo user modification' .
        //make it so that all users cant
        //do this
        //TODO add is admin column
        //'in <a href="/users.php">Modify Users</a>'.
        "";
  }

  public function getFooter(){
    return '&copy PiDiunoLock Web Interface';
  }

}


?>
