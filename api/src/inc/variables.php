<?php
function passwordEncode($password){
  return $password;
}
class variables {
  function appheader(){
    return array("apprequest" => "lock", "UDID" => "");
  }


  function passwordEncode($password){
    return $password;
  }

  function checkAuthy(){
    return '';
  }

  function checkPostVariables(){
    return '';
  }

  function emailVariables(){
    return array('name' => '', 'pass' => '');
  }

  function createTempPassword($user){
    $pass = hash('sha512', "this");
    return $pass;
  }
}
?>
