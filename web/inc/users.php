<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require_once("$root/../inc/template.php");
require_once("$root/../inc/extraFunctions.php");

class userEdit extends Page{

  public function getHeader(){
    return 'PiDuinoLock';
  }

  public function getBody(){
    if (isAdmin()){
      $db = new dbconn;
      $db->connect('read');
      $also = $db->getUsers();
      print_r($also);
      return 
        'You are logged in as an admin <a href="/logout.php">Log out</a>' .
          '<br>' .
          'Wooo user modification' .
          '<br>' .
          $also[0] .
          '<br>' .
          'Register a user:' .
          '<br>' .
          'FORM with name and pwd' .
          '';
      //'yes' .
    } else {
    return //"You are logged in";
      'You are logged in <a href="/logout.php">Log out</a>' .
        '<br>' .
        'Wooo user modification' .
        //TODO select all the users if 
        //the person is an admin
        '' .
        //make it so that all users cant
        //do this
        //TODO add is admin column
        //'in <a href="/users.php">Modify Users</a>'.
        "";
    }
  }

  public function getFooter(){
    return '&copy PiDuinoLock Web Interface';
  }

}


?>
