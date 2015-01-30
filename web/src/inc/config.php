<?php
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//include_once("$root/../inc/member.php");
//require_once("$root/../inc/member.php");

class ConfigPage {
//class ConfigPage extends Member {

  private $config = null;

  public function __construct(){
    //
    $this->config = parse_ini_file("../properties/config.ini");
//    $this->config = array_merge($this->config, parse_ini_file("../properties/location.ini"));
    ksort($this->config);
  }

  public function getTitle(){
    return 'Config';
  }

  public function getBody(){
    return
//      'Here is the config info<br>' . 
//      $this->config['version'] . 
//      '<br>'.
//      $this->config['branch'] . 
//      '<br>'.
//      $this->config['webserver.root'] . 
//      '<br>'.
//      $this->config['env'] . 
//      '<br>'.
//      '<pre>' . 
//      //var_dump($this->config, true) . 
//      //var_dump($this->config) . 
//      //print_r($this->config) . 
//      json_encode($this->config, true) . 
//      //print_r($this->config, true) . 
//      '</pre>' . 
        json_encode($this->config, true) . 
        "";
  }

  final public function render(){
    header('Content-type: application/json');
//    $output = //'<!DOCTYPE html>' .
//      '<html>' .
//      '<head>' .
//        '<title>' .
//          $this->getTitle() .
//        '</title>' .
//      '</head>' .
//      '<body>'.
//        $this->getBody() . 
//      '</body>' .
//      '<html>';
//    echo $output;
    echo $this->getBody();
  }

}

?>

