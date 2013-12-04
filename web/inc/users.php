<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require_once("$root/../inc/member.php");
require_once("$root/../inc/extraFunctions.php");

class userEdit extends Member{

  public function getBody(){
    if (isAdmin()){
      $db = new dbconn;
      $db->connect('read');
      $also = $db->getUsers();
      echo '<pre>';
      print_r($also);
      echo '</pre>';
      //session_start();
      //TODO kill mysql db connectino
      return 
        'You are logged in as an admin <a href="/logout.php">Log out</a>' .
          '<br>' .
          'Wooo user modification' .
          '<br>' .
          //$also[0] .
          //$_COOKIE['n'] .
          //$_SESSION['foo'] .
          '<br>' .
          'You are ' . $_SESSION['isAdmin'] . ' admin' .
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
        '<br>' .
        'You are ' . $_SESSION['isAdmin'] . ' admin' .
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


}


?>
