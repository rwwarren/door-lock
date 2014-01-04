<?php
ini_set("session.hash_function", "sha512");
session_name('sid');
session_start();

abstract class Page {

  //Returns a header
  public function getHeader(){
    return 'PiDuinoLock';
  }

  //Needs to be defined in the children
  //returns the html body
  abstract function getBody();

  //Returns the page footer
  public function getFooter(){
    return '&copy PiDuinoLock Web Interface';
  }

  //Retuns the nav bar on the page
  public function getNav(){
    return
        '';
  }

  //Returns page scripts, like css and javascripts
  public function getScripts(){
    return
      '<script src="http://code.jquery.com/jquery-2.0.3.js"></script>' .
      '';
  }

  //Renders the page for the client
  final public function render(){
    $output = '<!DOCTYPE html>' .
      '<html>' .
      '<head>' .
        '<link rel="stylesheet" type="text/css" href="/css/styles.css" />' .
        //'<link rel="stylesheet" type="text/css" href="/js/" />' .
        $this->getScripts() .
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
            $this->getNav() .
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
