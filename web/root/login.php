<?php
  //public function login() {
    //login to site
    //$db = new dbconn;
    //start session and stayed logged in
    /*
    if (array_key_exists('Username', $_POST) && array_key_exists('Password', $_POST)) {
      $name = $_POST['Username'];
      $password = $_POST['Password'];
      $user = $db->login($name, $password);
      if($username == 'demo' && $password == 'demo'){
        echo "success";
      }
      //here
      //some login
      //jQuery / js stuff
    }*/
  //}
//TODO remove this
if(isset($_POST)){
  echo $_POST['Username'];
  echo "<br>";
  echo $_POST['Password'];
} else {
  echo "nope";
}
$_POST['Username'] = session_id();
session_start();
echo "<br> test";
header("Location:http://doorlock.wrixton.net/");
?>
