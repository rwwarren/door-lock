<?php
require_once("$root/../inc/template.php");
class Home extends Page {

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
        //testing
        //TODO work this with the login
        '<a href="http://jquery.com/">jQuery</a>' .
        '<script src="jquery.js"></script>' .
        '<script>' .
 
        '$( document ).ready(function() {' .
          '$( "a" ).click(function( event ) {' .
              'alert( "The link will no longer take you to jquery.com" );' .
                  'event.preventDefault();' .
              '});' .
            '});' .
 
        '</script>' .
        //here was ther submit
        //'<br>' .
        //'<a href="#" class="enviar">Submit</a>' .
      '</form>' .
      '</section>';
    //return 'This is a little test';
  }

}
?>
