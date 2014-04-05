<?php
ini_set("session.hash_function", "sha512");
session_name('sid');

//header("Set-Cookie: cookiename=sid; expires=Tue, 06-Jan-2009 23:39:49 GMT; path=/; domain=doorlock.wrixton.net");
//ini_set('session.cookie_domain', '.wrixton.net');
//session_set_cookie_params(0, '/', '.wrixton.net');

session_start();

abstract class Page {

  //Title
  public function getTitle(){
    return 'PiDuinoLock';
  }

  //Returns a header
  public function getHeader(){
    //return 'PiDuinoLock';
    return '<a href="/">PiDuinoLock</a>';
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
      //'<script src="http://code.jquery.com/jquery-2.0.3.js"></script>' .
      '<script src="https://code.jquery.com/jquery-2.0.3.js"></script>' .
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
          $this->getTitle() .
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
