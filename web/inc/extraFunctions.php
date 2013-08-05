<?php

  function isLoggedIn(){
    return isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['sid']) && isset($_COOKIE['n']); 
  }

  //TODO Make it better in the future
  function isAdmin(){
    return $_COOKIE['n'] == 'Ryan';
  }
?>
