<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/../inc/dbcon.php");
require_once("$root/../inc/member.php");
require_once("$root/../inc/extraFunctions.php");

class userEdit extends Member{

  public function getScripts(){
    $html =
      //'<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">' .
      //'<script src="http://code.jquery.com/jquery-1.9.1.js"></script>' .
      //'<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>' .
      '<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">' .
      '<script src="https://code.jquery.com/jquery-1.9.1.js"></script>' .
      '<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>' .
      //Testing below
      '<script src="/js/checkText.js"></script>' .

      '<style>'.
        'div#users-contain { width: 350px; margin: 20px 0; }'.
        'div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }'.
        'div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }'.
        '.ui-dialog .ui-state-error { padding: .3em; }'.
        '.validateTips { border: 1px solid transparent; padding: 0.3em; }'.
      '</style>'.
      ' <style>' .
        //'body { font-size: 62.5%; }' .
        'label, input { display:block; }' .
        'input.text { margin-bottom:12px; width:95%; padding: .4em; }' .
        'fieldset { padding:0; border:0; margin-top:25px; }' .
        'h1 { font-size: 1.2em; margin: .6em 0; }' .
        'div#users-contain { width: 350px; margin: 20px 0; font-size: 65%;}' .
        'div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; font-size: 65%;}' .
        'div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; font-size: 65% }' .
        '.ui-dialog .ui-state-error { padding: .3em; }' .
        '.validateTips { border: 1px solid transparent; padding: 0.3em; }' .
      '</style>' .

      '<script>' .
      '$(function() {' .
        'var oldpassword = $( "#oldpassword" ),' .
          'newpassword = $( "#newpassword" ),' .
         'allFields = $( [] ).add(\'test\'),' .
          'tips = $( ".validateTips" );' .
    
     
        'function updateTips( t ) {' .
          'tips' .
            '.text( t )' .
            '.addClass( "ui-state-highlight" );' .
          'setTimeout(function() {' .
            'tips.removeClass( "ui-state-highlight", 1500 );' .
          '}, 500 );' .
        '}' .
     
        'function checkLength( o, n, min, max ) {' .
          'if ( o.val().length > max || o.val().length < min ) {' .
            'o.addClass( "ui-state-error" );' .
            'updateTips( "Length of " + n + " must be between " +' .
              'min + " and " + max + "." );' .
            'return false;' .
          '} else {' .
            'return true;' .
          '}' .
        '}' .
     
        'function checkRegexp( o, regexp, n ) {' .
          'if ( !( regexp.test( o.val() ) ) ) {' .
            'o.addClass( "ui-state-error" );' .
            'updateTips( n );' .
            'return false;' .
          '} else {' .
            'return true;' .
          '}' .
        '}' .
     
        '$( "#password-form" ).dialog({' .
          'autoOpen: false,' .
          'height: 325,' .
          'width: 350,' .
          'modal: true,' .
          'buttons: {' .
            //TODO add about the post and add so it allows to the
            //page
            '"Change Password": function() {' .
              'var bValid = true;' .
              'allFields.removeClass( "ui-state-error" );' .
     
              'bValid = bValid && checkLength( oldpassword, "oldpassword", 3, 16 );' .
              //bValid = bValid && checkLength( username, "username", 3, 16 );
              //bValid = bValid && checkLength( email, "email", 6, 80 );
              'bValid = bValid && checkLength( newpassword, "newpassword", 5, 16 );' .
     
              'bValid = bValid && checkRegexp( oldpassword, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );' .
              'bValid = bValid && checkRegexp( newpassword, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );' .
     
              'if ( bValid ) {' .
                '$( this ).dialog( "close" );' .
                '$.ajax({ url: \'/changePassword.php\',' .
                  'data:{oldPassword: oldpassword.val(), newPassword: newpassword.val() },' .
                  'type: \'post\',' .
                  'statusCode: {' .
                    '200: function() {' .
                      'document.location.href=\'/\'' .
                    '},' .
                    '401: function() {' .
                      'alert("Password Incorrect");' .
                      //window.location.reload();
                    '},' .
                    '403: function() {' .
                      'alert("Error User or Password Wrong");' .
                      //document.location.href='/'
                    '}' .
                  '}' .
                '});' .
              '}' .
            '},'.
            'Cancel: function() {' .
              '$( this ).dialog( "close" );' .
            '}' .
          '},' .
          'close: function() {' .
            'allFields.val( "" ).removeClass( "ui-state-error" );' .
          '}' .
        //'});' .
        '}).css("font-size", "65%");' .
     
        '$( "#change-pass" )' .
          '.button()' .
          '.click(function() {' .
            '$( "#password-form" ).dialog( "open" );' .
          '});' .
      //'});' .
        '}).css("font-size", "65%");' .
      '</script>'.
    '';
  echo $html;
          
  }

  //TODO clean up, div class maybe, label, make nice
  //get all info, change only what changed, add in current info
  private function userEditer(){
    $dbconn = new dbconn("read");
    //$dbconn->connect('read');
    $userInfo = $dbconn->getUserInfo($_SESSION['username']);
//    if($userInfo['AuthyID'] == 0){
//      $userInfo['AuthyID'] = NULL;
//    }
    //print_r($userInfo);
    //$dbconn->close();
    return
      'Username: ' .
      $_SESSION['username'] .
      '<div id="changes">' .
//        '<form name="input" action="/changeUserInfo.php" method="post">' .
        '<form name="input" onsubmit="check_users(); return false" method="post" enctype="application/x-www-form-urlencoded">' .
          '<fieldset>' .
            'Name: <input type="text" name="name" id="name" placeholder="' . $userInfo['Name'] .'">' .
            'Email: <input type="text" name="email" id="email" placeholder="' . $userInfo['Email'] .'">' .
            'Card ID: <input type="text" name="card" id="card" placeholder="' . $userInfo['CardID'] . '">' .
            'Authy ID: <input type="text" name="authy" id="authy" placeholder="' . $userInfo['AuthyID'] . '">' .
            'Current Password: <input type="password" name="oldPwd" id="oldPwd" onkeyup="checkPass($(this).val(), $(\'#confNewPwd\').val(), $(\'#newPwd\').val())">' .
//            'Current Password: <input type="password" name="oldPwd" id="oldPwd" onkeyup="checkPass(this, $(\'#confNewPwd\').val(), $(\'#newPwd\').val())">' .
            'New Password: <input type="password" name="newPwd" id="newPwd" onkeyup="checkPass($(\'#oldPwd\').val(), $(this).val(), $(\'#confNewPwd\').val())">' .
//            'Confirm New Password: <input type="password" name="confNewPwd" id="confNewPwd">' .
            'Confirm New Password: <input type="password" name="confNewPwd" id="confNewPwd" onkeyup="checkPass($(\'#oldPwd\').val(), $(this).val(), $(\'#newPwd\').val())">' .
            '<input id="submit" type="submit" value="Submit" disabled>' .
          '</fieldset>' .
        '</form>' .
        '<div id="checkPass"></div>' .
        '<script>' .
          //'function isValid(pass){' .
          //  'return false;'.
          //'};' .
          'function check_users() {' .
            '$.ajax({type: \'POST\', url: \'/changeUserInfo.php\', data: { name: $(\'#name\').val(), ' .
                  'oldPwd: $(\'#oldPwd\').val(), newPwd: $(\'#newPwd\').val(), authy: $(\'#authy\').val(),' .
                  'email: $(\'#email\').val(), card: $(\'#card\').val(), confNewPass: $(\'#confNewPwd\').val()' .
                  '}, statusCode: {' .
                    '200: function(){' .
                      'location.reload();' .
                  '},' .
                    '401: function(){' .
                    '$(\'#checkPass\').html(\'Error, wrong password!\')' .
                  '}' .
                '}' .
              '});' .
              //'console.log("testing");' .
          '};' .
          //'function isValid(pass){' .
          //  'return false;'.
          //'}' .
        '</script>' .
      '</div>' .
    '';
  }

  private function registerUser(){
    $html =
      '<div id="smallelements">' .
        '<div id="dialog-form" title="Create new user">' .
        '<p class="validateTips">All form fields are required.</p>' .

        '<form>' .
        '<fieldset>' .
          '<label for="name">Name</label>' .
          '<input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all">' .
          '<label for="username">Username</label>' .
          '<input type="text" name="username" id="username" value="" class="text ui-widget-content ui-corner-all">' .
          '<label for="authyID">AuthyID</label>' .
          '<input type="text" name="authyID" id="authyID" value="" placeholder="If none, leave blank" class="text ui-widget-content ui-corner-all">' .
          '<label for="email">Email</label>' .
          '<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">' .
          '<label for="password">Password</label>' .
          '<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all">' .
          '<label for="isAdmin">Is the user an admin?</label>' .
          '<input type="checkbox" name="isAdmin" id="isAdmin" value="" >' .
        '</fieldset>' .
        '</form>' .
      '</div>' .
      '<button id="create-user">Create new user</button>' .
    '</div>' .
    '';
    return $html;
  }

  public function getBody(){
    return //"You are logged in";
      'You are logged in <a href="/logout.php">Log out</a>' .
        '<br>' .
        'Wooo user modification' .
        '<br>' .
        'You are ' . $_SESSION['isAdmin'] . ' admin' .
        '<br>' .
        '<br>' .
        '<br>' .
        $this->userEditer() .
//        TODO add 2 factor authentication option here
        //TODO select all the users if 
        //the person is an admin
        '' .
        //make it so that all users cant
        //do this
        //TODO add is admin column
        //'in <a href="/users.php">Modify Users</a>'.
        "";
//    }
  }


}


?>
