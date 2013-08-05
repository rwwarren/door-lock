<?php
require_once("$root/../inc/template.php");
class Home extends Page {

  public function getHeader(){
    return 'PiDuinoLock';
  }

  public function getBody(){
    return '<section class="login">' .
        '<div class="titulo">User Login</div>' .
        //'<form action="#" method="post" enctype="application/x-www-form-urlencoded">' .
        '<form action="login.php" method="post" enctype="application/x-www-form-urlencoded">' .
        '<input type="text" required title="Username required" placeholder="Username" name="Username" data-icon="U">' .
        '<input type="password" required title="Password required" placeholder="Password" name="Password" data-icon="x">' .
        //'<div class="olvido">' .
        //        '<div class="col"><a href="#" title="Ver Carásteres">Register</a></div>' .
        //    '<div class="col"><a href="#" title="Recuperar Password">Fotgot Password?</a></div>' .
        //'</div>' .
        '<div id="loginSpace"> </div>' .
        //This is the button
        '<input type="submit" value="Submit" class="enviar">'.
        //here was ther submit
        //'<br>' .
        //'<a href="#" class="enviar">Submit</a>' .
      '</form>' .
      '</section>';
    //return 'This is a little test';
  }

  public function getFooter(){
    return '&copy PiDuinoLock Web Interface';
  }

//}

  public function login() {
    //login to site
    $db = new dbconn;
    //start session and stayed logged in
    
    if (array_key_exists('Username', $_POST) && array_key_exists('Password', $_POST)) {
      $name = $_POST['Username'];
      $password = $_POST['Password'];
      $user = $db->login($name, $password);
      if($username == 'demo' && $password == 'demo'){
        echo "success";
      }
      //here
      //some login
      //jQuery / js stuff
    }
  }
}
?>
