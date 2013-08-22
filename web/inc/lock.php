<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/member.php");

class lock extends Member {

  public function getBody(){

    return //"You are logged in";
      'You are logged in <a href="/logout.php">Log out</a>' .
        '<br>' .
        'Welcome '. $_SESSION['userName'] .
        '<br>' .
        'This is the lock screen' . 
        "";
  }

}

?>
