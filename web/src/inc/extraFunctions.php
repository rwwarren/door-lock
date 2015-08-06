<?php

//TODO remove this


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
    if(!isset($_SESSION['username']) || $_SESSION['username'] === null){
      $_SESSION['username'] = $results['LoggedIn'];
      //$_SESSION['username'] = $results['username'];
      $_SESSION['name'] = $results['Name'];
      $_SESSION['isAdmin'] = $results['IsAdmin'];
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


    function isMobile(){
      $useragent=$_SERVER['HTTP_USER_AGENT'];
      //TODO set if they want to see the mobile site
      //if($_SERVER['mobileViewNormal']){
      //  return false;
      //}
      return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    }
?>
