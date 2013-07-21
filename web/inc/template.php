<?php

abstract class Page {

  abstract function getHeader();

  abstract function getBody();

  abstract function getFooter();
  
  final public function render(){
    $output = '<!DOCTYPE html>' .
      '<html>' . 
      '<head>' .
        '<link rel="stylesheet" type="text/css" href="/css/styles.css" />' .
        '<title>' .
          $this->getHeader() .
        '</title>' . 
      '</head>' .
      '<body>'.
        '<div id="container">' .
        '<div id="header">' .
          $this->getHeader() .
        '</div>' .
          '<div id="body">' .
            $this->getBody() .
          '</div>' .
          '<div id="footer">' .
          $this->getFooter() .
          '</div>' .
        '</div>' .
      '</body>' .
      '<html>';
    echo $output;
  }

}

?>
