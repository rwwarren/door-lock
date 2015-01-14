<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/member.php");

class ForgotPage extends Page {

  public function getBody(){

    return
        '<h1>Password reset form:</h1>' .
        '<form action="resetPassword.php" method="post">' .
          'Username: <input type="text" name="username">' .
          '<br>' .
          'Email: <input type="text" name="email">' .
          '<input type="submit" value="Submit">'.
        '</form>' .
        "";
  }

}

?>
