<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/member.php");
//require '../../../Predis/Autoloader.php';
//TODO include predis
//echo $root;
//require("$root/../../Predis/Autoloader.php");
//Predis\Autoloader::register();

class Loggedin extends Member {

  public function getBody(){
    return //"You are logged in";
      'You are logged in <a href="/logout.php">Log out</a>' .
        '<br>' .
        'Welcome '. $_SESSION['name'] .
        '<br>' .
        'in <a href="/users.php">Modify Users</a>'.
        "";
  }

}

?>
