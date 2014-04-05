<?php
require_once("$root/../inc/template.php");
class Home extends Page {

  public function getBody(){
//        print_r($_SESSION);
    return '<section class="login">' .
        '<div class="titulo">User Login</div>' .
        //'<form action="#" method="post" enctype="application/x-www-form-urlencoded">' .
        //'<form action="login.php" method="post" enctype="application/x-www-form-urlencoded">' .
        //TODO this is the one i like below
        '<form onsubmit="check_login(); return false" method="post" enctype="application/x-www-form-urlencoded">' .
        '<input type="text" class="top" required title="Username required" placeholder="Username" name="Username" id="username" data-icon="U">' .
        '<input type="password" required title="Password required" placeholder="Password" name="Password" id="password" data-icon="x">' .
        '<input type="text" class="bottom" required title="Token required" placeholder="Token" name="Token" id="token" data-icon="x">' .
        //'<div class="olvido">' .
        //        '<div class="col"><a href="#" title="Ver Carásteres">Register</a></div>' .
        //    '<div class="col"><a href="#" title="Recuperar Password">Fotgot Password?</a></div>' .
        //'</div>' .
        '<div id="loginSpace"> </div>' .
        //This is the button
        '<input type="submit" value="Submit" class="enviar">'.
        '<p align="center"><a href="/forgotpassword.php">Forgot Password</a></p>' .
        '<div id="loginStatus"> </div>' .
        //testing
        //TODO work this with the login
        /*
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
         */
        '<script>' .
        '$(document).ready(function(){$(\'#username\').focus()});' .
          'function check_login(){' .
            '$.ajax({' .
              'type:\'POST\',' .
              //'url:\'login.php\', ' .
              //'url:\'http://api.doorlock.wrixton.net/login/\', ' .
              'url:\'http://api.doorlock.wrixton.net/login\', ' .
              //'url:\'api.doorlock.wrixton.net/login\', ' .
              'data: { Username: $(\'#username\').val(), Password: $(\'#password\').val(), Token: $(\'#token\').val()},' .
              //'crossDomain : true,' .
              //'request.setRequestHeader("Access-Control-Allow-Origin: ", "*"),' .
              //'data: { Username: $(\'#username\').val(), Password: $(\'#password\').val(), Token: $(\'#token\').val()},' .
              //'data: { Username: $(\'#username\').val(), Password: $(\'#password\').val()},' .
              //'data:"username="+$(\'#username\').val()+"&password="+$(\'#password\').val(),' .
              'statusCode: {' .
                '200: function() {' .
                  'window.location = \'/\';' .
                '},' .
                '403: function(){ ' .
                  '$(\'#loginStatus\').css({\'color\':\'#cccccc\',\'display\':\'block\'}).html(\'Error, wrong username or password!\')' .
                '},' . 
                '401: function(){ ' .
                  '$(\'#loginStatus\').css({\'color\':\'#cccccc\',\'display\':\'block\'}).html(\'Error, Token invalid!\')' .
                '},' . 
                '400: function(){ ' .
                  '$(\'#loginStatus\').css({\'color\':\'#cccccc\',\'display\':\'block\'}).html(\'Error, username or password no entered!\')' .
                '}' . 
              '}' . 
              /*'success:function(response){' .
                'if(response== true){$(\'#error\').css({\'color\':\'#0c0\',\'display\':\'block\'}).html(\'CORRECT!\')}' .
                'else if(response==\'2\'){$(\'#error\').css({\'color\':\'red\',\'display\':\'block\'}).html(\'Login credentials incorrect!\')}' .
                'else if(response==\'3\'){$(\'#error\').css({\'color\':\'red\',\'display\':\'block\'}).html(\'Please fill in all fields\')} ' .
              '}' .*/
          '});' .
        '};' .
        '</script>' .
        //here was ther submit
        //'<br>' .
        //'<a href="#" class="enviar">Submit</a>' .
      '</form>' .
      '</section>' .
        '<label class=\'error\' id=\'error\' style=\'display: none; font-size: 12px;\'></label>' .
        //
        //
      '';
    //return 'This is a little test';
  }

}
?>
