<?php

namespace ApiClient;

class ApiClient{

  private $apiKey;
  private $apiUrl;

  public function __construct(){
    $root = realpath(dirname(__FILE__));
    $config = parse_ini_file("$root/../properties/secure.ini");
    $this->apiKey = $config['api.key'];
    $this->apiUrl = $config['api.url'];

  }

  private function setUpRequest(){
    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");
  }

  public function login(){
//    $this->setUpRequest();
    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, "$this->apiUrl/login");
    curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-DoorLock-Api-Key: $this->apiKey"));
//    curl_setopt($ch,CURLOPT_POST, count($fields));
//    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_POSTFIELDS, array('username'=>'test','password'=>'password'));
//    curl_setopt($ch,CURLOPT_POSTFIELDS, array('username'=>'test','password'=>'password', 'DoorLock-Api-Key'=>'test'));

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    return $result;
  }

  public function logout(){

  }

  public function changePassword(){

  }

  public function forgotPassword(){

  }

  public function lockStatus(){

  }

  public function lock(){

  }

  public function unlock(){

  }

}

?>