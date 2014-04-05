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

      '<style>'.
        'div#users-contain { width: 350px; margin: 20px 0; }'.
        'div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }'.
        'div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }'.
        '.ui-dialog .ui-state-error { padding: .3em; }'.
        '.validateTips { border: 1px solid transparent; padding: 0.3em; }'.
      '</style>'.
      '';

    if (isAdmin()) {
    $html .=
      //'<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">' .
      //'<script src="http://code.jquery.com/jquery-1.9.1.js"></script>' .
      //'<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>' .
      '<style>' .
      '#admin, #active, #inactive { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}' .
      '#admin li, #active li, #inactive li { margin: 5px; padding: 5px; font-size: 1.2em; width: 120px; }' .
      '.admin {border: 1px solid #aaaaaa; background: #ffffff; font-weight: normal; color: #212121}'.
      '.active {border: 1px solid #999999; background: #dadada url(/css/ui-glass_active.png) 50% 50% repeat-x; font-weight: normal; color: #212121}'.
      '.inactive {border: 1px solid #d3d3d3; background: #e6e6e6 url(/css/ui-glass_inactive.png) 50% 50% repeat-x; font-weight: normal; color: #555555}'.
    
      '#testing {height: 250px;}'.
      '</style>' .
      '<script>' .
      '$(function() {' .
      '$( "ul.connectedSortable" ).sortable({' .
        'connectWith: "ul"' . ',' .
        //
        'items: "li:not(.currentUser)",'.
        //
        'update: function(event, ui){' .
          'if(ui.sender){'.
            //'var info = new Array();'.
            //'var original = '. json_encode($admin) . ';'.
            //'info["og"] = original;' .
            //'var changed = $(this).sortable(\'toArray\');'.
            //'info["new"] = changed;'.
            'var updated = ui.item.attr(\'id\');'.
            'var old = ui.item.attr(\'class\');'.
            'var type = event.target.id;' .
            '$.ajax({' .
            'type: "POST",' .
              'url: "changeUser.php",' .
              'data: {type: type, user: updated},'.
              'statusCode: {' .
                '200: function() {' .
                  'ui.item.removeClass(old);'.
                  'ui.item.addClass(type);'.
                '},' .
                '400: function() {'.
                  'alert("Error! Please refresh the page!");'.
                  'window.location.reload();'.
                '},'.
                '403: function() {'.
                  'alert("Can not make changes! Please refresh the page!");'.
                  //'window.location.reload();'.
                  'document.location.href=\'/\''.
                '}'.
              '}' .
            '});' .
          '}'.
        '}'.
      '});'.
      '$( "#admin, #active, #inactive" ).disableSelection();' .
      //'$(function(){'.
        //'$("ul.connectedSsortable").sortable({items: "li:not(.unsortable)"});' .
      //'$("ul.connectedSsortable").sortable({cancel: ".unsortable"});' .
      //'});'.
      '});' .
      '</script>' .
      /*' <style>' .
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
        '</style>' .*/
      '<script>' .
        '$(function() {' .
          'var name = $( "#name" ),' .
            'email = $( "#email" ),' .
            'password = $( "#password" ),' .
            'username = $( "#username" ),' .
            'isAdmin = false;' .
            'authyID = $( "#authyID" ),' .
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
              '  min + " and " + max + "." );' .
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
          
          '$( "#dialog-form" ).dialog({' .
            'autoOpen: false,' .
            'height: 435,' .
            'width: 350,' .
            'modal: true,' .
            'buttons: {' .
              '"Create an account": function() {' .
                'var bValid = true;' .
                'allFields.removeClass( "ui-state-error" );' .
                'bValid = bValid && checkLength( name, "name", 3, 16 );' .
                'bValid = bValid && checkLength( username, "username", 3, 16 );' .
                'bValid = bValid && checkLength( email, "email", 6, 80 );' .
                'bValid = bValid && checkLength( password, "password", 5, 16 );' .
                'bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );' .
                //'bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );' .
                'bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&\'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&\'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );' .
                'bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );' .
                
                'if ( bValid ) {' .
                  '$( this ).dialog( "close" );' .
                  '$.ajax({ url: \'/register.php\',' .
                    'data: {personName: name.val(), username: username.val(), password: password.val(), email: email.val(), authyID: authyID.val(), admin: $(\'#isAdmin\').is(\':checked\') },' .
                    'type: \'post\',' .
                    'statusCode: {' .
                      '200: function() {' .
                        'if($(\'#isAdmin\').is(\':checked\')){' .
                          '$(\'#admin\').append(\'<li id="\' + username.val() + \'" class="admin">\'+ username.val() + \'</li> \');' .
                        '} else {' .
                          '$(\'#active\').append(\'<li id="\' + username.val() +\'" class="active">\' + username.val() + \'</li> \');' .
                        '}' .
                        'window.location.reload();' .
                      '},' .
                      '403: function() {' .
                        'alert("Can not make changes! Please refresh the page!");' .
                        'document.location.href=\'/\''.
                      '}' .
                    '}' .
                  '});' .
                '}' .
              '},' .
              'Cancel: function() {' .
                '$( this ).dialog( "close" );' .
              '}' .
            '},' .
            'close: function() {' .
              'allFields.val( "" ).removeClass( "ui-state-error" );' .
            '}' .
          '}).css("font-size", "65%");' .
          
          '$( "#create-user" )' .
            '.button()' .
            '.click(function() {' .
              '$( "#dialog-form" ).dialog( "open" );' .
            '});' .
          '}).css("font-size", "65%");' .
          '</script>' .
          '';
    }
    $html .=
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

  private function userconfig(){
    $db = new dbconn;
    $db->connect('write');
    //$db->connect('read');
    $also = $db->getUsers();
    $inactive = $db->getInactiveUsers();
    $active = $db->getActiveUsers();
    $admin = $db->getAdmins();
    $html =
      '<div id="elements">'.
        '<ul id="admin" class="connectedSortable">' .
        '';
          for ($i = 0; $i < sizeof($admin); $i++){
            if ($admin[$i] == $_SESSION['username']){
              $html .= '<li id="' . $admin[$i] . '" class="currentUser">'. $admin[$i] . '</li>' ;
            } else {
              $html .= '<li id="' . $admin[$i] . '" class="admin">'. $admin[$i] . '</li>' ;
            }
            //$html .= '<li id="' . $admin[$i] . '" class="admin">'. $admin[$i] . '</li>' ;
            //$html .= '<li id="' . $admin[$i] . '" class="ui-state-active">'. $admin[$i] . '</li>' ;
          }
        $html .=
        '</ul>' .
         
        '<ul id="active" class="connectedSortable">' .
        //'<ul id="admin" class="connectedSortable">' .
        '';
          for ($i = 0; $i < sizeof($active); $i++){
            $html .= '<li id="' . $active[$i] . '" class="active">'. $active[$i] . '</li>' ;
          }
        $html .=
        '</ul>' .
        
        '<ul id="inactive" class="connectedSortable">' .
        //'<ul id="admin" class="connectedSortable">' .
        '';
          for ($i = 0; $i < sizeof($inactive); $i++){
            $html .= '<li id="' . $inactive[$i] . '" class="inactive">'. $inactive[$i] . '</li>' ;
          }
        $html .=
        '</ul>' .
      '</div>'.
      '';
      return $html;

  }

  private function changePass(){
    return
      '<div id="smallelements">'.
        '<div id="password-form" title="Change Password">' .
          '<p class="validateTips">All form fields are required.</p>' .
         
          '<form>' .
          '<fieldset>' .
            '<label for="oldpassword">Old Password:</label>' .
            '<input type="password" name="oldpassword" id="oldpassword" value="" class="text ui-widget-content ui-corner-all">' .
            '<label for="newpassword">New Password:</label>' .
            '<input type="password" name="newpassword" id="newpassword" value="" class="text ui-widget-content ui-corner-all">' .
          '</fieldset>' .
          '</form>' .
        '</div>' .
        '<button id="change-pass">Change Password</button>' .
      '</div>'.
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
    if (isAdmin()){
      $db = new dbconn;
      $db->connect('read');
      $also = $db->getUsers();
      //echo '<pre>';
      //print_r($also);
      //echo '</pre>';
      //A
      //session_start();
      //TODO kill mysql db connectino
      return 
        'You are logged in as an admin <a href="/logout.php">Log out</a>' .
          '<br>' .
          'Wooo user modification' .
          '<br>' .
          //$also[0] .
          //$_COOKIE['n'] .
          //$_SESSION['foo'] .
          '<br>' .
          'You are ' . $_SESSION['isAdmin'] . ' admin' .
          '<br>' .
          'Register a user:' .
          '<br>' .
          'FORM with name and pwd' .
          $this->userconfig() .
          $this->registerUser() .
          $this->changePass() .
          '';
      //'yes' .
    } else {
    return //"You are logged in";
      'You are logged in <a href="/logout.php">Log out</a>' .
        '<br>' .
        'Wooo user modification' .
        '<br>' .
        'You are ' . $_SESSION['isAdmin'] . ' admin' .
        $this->changePass() .
        //TODO select all the users if 
        //the person is an admin
        '' .
        //make it so that all users cant
        //do this
        //TODO add is admin column
        //'in <a href="/users.php">Modify Users</a>'.
        "";
    }
  }


}


?>
