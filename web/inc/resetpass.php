<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/member.php");

class ResetPage extends Page {

  public function getBody(){

    return 
        '<h1>Password reset form:</h1>' .
        '<form action="/submitPassReset/' . $_GET['resetToken'] .'" method="post">' .
          'Password: <input type="password" name="pass">' .
          '<br>' .
          'Confirm Password: <input type="password" name="confirmPass">' .
          '<input type="submit" value="Submit">'.
        '</form>' .
        "";
  }

}

?>
