<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/template.php");

class Error404Page extends Page {

  public function getBody(){
    return '404 Error page:' .
      '<br>' .
      'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] .
      '<br>' .
      'not found' .
      '<br>' .
      '<a href="/">Back to Home</a>' .
      '' .
      '';
  }
}

$page = new Error404Page;
$page->render();

?>
