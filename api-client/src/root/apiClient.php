<?php

class apiClient{

  private $apiKey;
  private $apiUrl;

  public function __construct(){
    $root = realpath(dirname(__FILE__));
    $config = parse_ini_file("$root/../properties/secure.ini");
    $this->apiKey = $config['api.key'];
    $this->apiUrl = $config['api.url'];

  }

  public function login(){

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