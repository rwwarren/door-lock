<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once("$root/../inc/dbcon.php");
include_once($root . "/../vendor/door-lock/api-client/src/root/apiClient.php");


  //Returns if the user is logged in
  function isLoggedIn(){
    global $root;
//    echo "cheking login";
//    print_r($_SESSION);
//    $root = realpath(dirname(__FILE__));
//    echo $root ."/../../../../door-lock-api-client/src/root/apiClient.php";
//    include_once($root . "/../../../../door-lock-api-client/src/root/apiClient.php");
//    print_r($_COOKIE);
//    print_r($_SESSION);
//    session_start();
//    $apiClient = new ApiClient;
    if(!isset($_COOKIE['sid'])){
      return false;
    }
    $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
    $results = $apiClient->isLoggedIn($_COOKIE['sid']);
//    print_r($results);
//    $intermediate = json_decode($results);
//    $decoded = json_decode($intermediate, true);
    if($results['success'] == 0){
      return false;
    }
    if(!isset($_SESSION['username']) && $_SESSION['username'] === null){
      $_SESSION['username'] = $results['username'];
      $_SESSION['name'] = $results['name'];
      $_SESSION['isAdmin'] = $results['isAdmin'];
    }
    return true;
//    return $decoded['success'] == 1;
//    return $apiClient->isLoggedIn($_SESSION['sid']);
//    return isset($_SESSION['username']) && $_SESSION['username'] !== null;
  }

  //Returns if the user is an admin
  function isAdmin(){
    if(!isset($_COOKIE['sid'])){
      return false;
    }
    global $root;
    $apiClient = new ApiClient\ApiClient("$root/../properties/secure.ini");
    return $apiClient->isAdmin($_COOKIE['sid']);
//    return $apiClient->isAdmin($_COOKIE['sid']) === true;
//    return isLoggedIn() && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1;
  }

  //Returns if the reset token is valid
  function checkTokenValid($resetToken){
    //return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1;
    $dbconn = new dbconn("read");
    //$dbconn->connect("read");
    $results = $dbconn->findResetToken($resetToken);
    //$dbconn->close();
    return $results;
  }

  //Sends the user an email about password being reset,
  //send the name, and reset url
  function sendMail($name, $sendEmail, $newPassToken){
    global $root;

    include_once 'variables.php';
    //include '../includedPackages/PHPMailer/PHPMailerAutoload.php';
    require_once("$root/../vendor/autoload.php");
    $emailVars = emailVariables();
    $mail = new PHPMailer;
    
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp-mail.outlook.com';              //';pop3.live.com';  // Specify main and backup server
    $mail->Port = 587;
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $emailVars['name'];                            // SMTP username
    $mail->Password = $emailVars['pass'];                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
    
    $mail->From = $emailVars['name'];
    $mail->FromName = 'Doorlock Admin';
    //query for the $user email
    //to name
    //$name = '';
    //$sendEmail = '';
    $mail->addAddress($sendEmail, $name);  // Add a recipient
    
    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    $mail->isHTML(true);                                  // Set email format to HTML
    
    $mail->Subject = 'Here is the subject';
    //$newPassword = '';
    
    $htmlbody = 'Your password for doorlock.wrixton.net has been reset <br>' .
          'Please click <a href="https://doorlock.wrixton.net/forgotPassword/' . $newPassToken . '">here</a>' .
          '<br> Or please copy and paste the link below: <br>' .
          'https://doorlock.wrixton.net/forgotPassword/' . $newPassToken .
              '';

    $altBody = 'Your password for doorlock.wrixton.net has been reset. Please copy and paste the link: ' .
          'https://doorlock.wrixton.net/forgotPassword/' . $newPassToken .
          '';

    //$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->Body    = $htmlbody;
    //TODO make these say the same things
    $mail->AltBody = $altBody;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
    $mailStatus = $mail->send();
    //if(!$mailStatus /*!$mail->send()*/) {
    if(!$mailStatus) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
    }
    
    echo 'Message has been sent';
    return $mailStatus;
  }

  //function checkHeaders(){
  //  if (isset($_SERVER['HTTP_REFERER']) == "http://doorlock.wrixton.net/"){
  //    return true;
  //  } else {
  //    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
  //    include("$root/../inc/variables.php");
  //    $sentHeaders = getallheaders();
  //    unset($sentHeaders['User-Agent']);
  //    unset($sentHeaders['Host']);
  //    unset($sentHeaders['Accept']);
  //    unset($sentHeaders['Content-Length']);
  //    unset($sentHeaders['Content-Type']);
  //    if ($requiredHeaders == $sentHeaders){
  //      return true;
  //    } else {
  //      return false;
  //    }
  //  }
  //}

?>
