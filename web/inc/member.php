<?php
require_once("$root/../inc/template.php");

abstract class Member extends Page{

  public function getNav(){
    return
      '<div id="nav">' .
        '<ul>' .
          '<li><a href="/">Home</a></li>' .
          '<li><a href="/users/">User Info</a></li>' .
          '<li><a href="/lock/">Lock Status</a></li>' .
          '<li><a href="/logout.php">Logout</a></li>' .
        '</ul>' .
      '</div>';
  }

}

?>
