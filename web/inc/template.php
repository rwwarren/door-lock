<?php
//if (isset($_SESSION)){
session_name('sid');
session_start();
//}

abstract class Page {

  public function getHeader(){
    /*
    if (isset($_SESSION)){
      session_start();
    }
*/
    return 'PiDuinoLock';
  }

  abstract function getBody();

  public function getFooter(){
    return '&copy PiDuinoLock Web Interface';
  }

  public function getNav(){
    //only have if there is
    //someone logged in
    return
      /*
      '<div id="nav">' .
        '<ul>' .
          '<li><a href="/">Home</a></li>' .
          '<li><a href="/users.php">User Info</a></li>' .
          '<li><a href="/logout.php">Logout</a></li>' .
        '</ul>' .
        '</div>' .
       */
        '';
  }

  final public function render(){
    $output = '<!DOCTYPE html>' .
      '<html>' .
      '<head>' .
        '<link rel="stylesheet" type="text/css" href="/css/styles.css" />' .
        //'<link rel="stylesheet" type="text/css" href="/js/" />' .
        '<script src="http://code.jquery.com/jquery-2.0.3.js"></script>' .
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
