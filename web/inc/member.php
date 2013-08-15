<?php
require_once("$root/../inc/template.php");

abstract class Member extends Page{

  public function getNav(){
    //only have if there is
    //someone logged in
    return
      '<div id="nav">' .
        '<ul>' .
          '<li><a href="/">Home</a></li>' .
          '<li><a href="/users.php">User Info</a></li>' .
          '<li><a href="/lock.php">Lock Status</a></li>' .
          '<li><a href="/logout.php">Logout</a></li>' .
        '</ul>' .
      '</div>';
  }

}

?>
