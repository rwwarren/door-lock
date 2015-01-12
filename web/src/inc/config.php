<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/member.php");

class ConfigPage extends Member {

  private $config = null;

  public function __construct(){
    //
    $this->config = parse_ini_file("../properties/config.ini");
    $this->config = array_merge($this->config, parse_ini_file("../properties/location.ini"));
  }

  public function getBody(){
    return
      'Here is the config info<br>' . 
      $this->config['version'] . 
      '<br>'.
      $this->config['branch'] . 
      '<br>'.
      $this->config['webserver.root'] . 
      '<br>'.
      $this->config['env'] . 
      '<br>'.
      //print_r($this->config) . 
        "";
  }

}

?>

