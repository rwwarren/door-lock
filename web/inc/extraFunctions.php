<?php

  function isLoggedIn(){
    //return isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['sid']) && isset($_COOKIE['n']); 
    //return isset($_session['isloggedin']) && $_session['isloggedin'] == 1;
    return $_SESSION['userName'] !== null;
  }


  //TODO Make it better in the future
  function isAdmin(){
    return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1;
    //return $_COOKIE['n'] == 'Ryan';
  }
?>
