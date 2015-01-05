<?php
require_once("$root/../inc/template.php");

abstract class Member extends Page{

  public function getNav(){
    if(isAdmin()){
      return $this->adminNav();
    } else {
      return $this->normalNav();
    }
  }

  private function normalNav(){
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

  private function adminNav(){
    return
      '<div id="nav">' .
        '<ul>' .
          '<li><a href="/">Home</a></li>' .
          '<li><a href="/users/">User Info</a></li>' .
          '<li><a href="/lock/">Lock Status</a></li>' .
          '<li><a href="/admin/">Admin Page</a></li>' .
          '<li><a href="/logout.php">Logout</a></li>' .
        '</ul>' .
      '</div>';
  }

}

?>
