<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require_once("$root/../inc/template.php");

/*
echo '<br><br>';
echo 'This will allow access to the door lock one day';
echo '<br>PiDuinoLock';
echo '<br>';
 */

//$test = new users("write");
//echo '<br>';
class Home extends Page {

  public function getHeader(){
    return 'PiDuinoLock';
  }

  public function getBody(){
    return '<section class="login">' .
        '<div class="titulo">User Login</div>' .
        '<form action="#" method="post" enctype="application/x-www-form-urlencoded">' .
        '<input type="text" required title="Username required" placeholder="Username" data-icon="U">' .
        '<input type="password" required title="Password required" placeholder="Password" data-icon="x">' .
        //'<div class="olvido">' .
        //        '<div class="col"><a href="#" title="Ver Carásteres">Register</a></div>' .
        //    '<div class="col"><a href="#" title="Recuperar Password">Fotgot Password?</a></div>' .
        //'</div>' .
        '<div id="loginSpace"> </div>' .
        '<a href="#" class="enviar">Submit</a>' .
      '</form>' .
      '</section>';
    //return 'This is a little test';
  }

  public function getFooter(){
    return '&copy PiDiunoLock Web Interface';
  }

}

$db = new dbconn;
//start session and stayed logged in

if (array_key_exists('Username', $_POST) && array_key_exists('Password', $_POST)) {
  $name = $_POST['Username'];
  $password = $_POST['Password'];
  $user = $db->login($name, $password);
  //here
  //some login
  //jQuery / js stuff
}

$page = new Home;
$page->render();

?>
