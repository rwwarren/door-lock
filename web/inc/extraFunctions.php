<?php

  function isLoggedIn(){
    return isset($_COOKIE['PHPSESSID']) && isset($_COOKIE['sid']) && isset($_COOKIE['n']); 
  }
?>
