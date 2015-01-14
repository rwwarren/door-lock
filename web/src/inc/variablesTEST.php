<?php

//TODO fix this for auto deploy
$requiredHeaders = array("apprequest" => "lock", "UDID" => "");


function passwordEncode($password){
  return $password;
}

function checkAuthy(){

$authy_id = '';

}

function checkPostVariables(){

}

function emailVariables(){
  return array('name' => '', 'pass' => '');
}

function createTempPassword($user){
  $pass = hash('sha512', "this");
  return $pass;
}

?>
