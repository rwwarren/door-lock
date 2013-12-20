<?php

  function isLoggedIn(){
    return isset($_SESSION['username']) && $_SESSION['username'] !== null;
  }

  function isAdmin(){
    return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1;
  }

  function sendMail($name, $sendEmail, $newPassword){

    include_once 'variables.php';
    $emailVars = emailVarriables();
    $mail = new PHPMailer;
    
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp-mail.outlook.com';//';pop3.live.com';  // Specify main and backup server
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
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    //TODO make these say the same things
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
    $mailStatus = $mail->send();
    if(!$mailStatus /*!$mail->send()*/) {
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
