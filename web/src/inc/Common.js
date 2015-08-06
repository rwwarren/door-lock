var API_URL = "http://m.localhost/";
//var LOGIN = "login.php";
//var CHECK_LOGIN = "checklogin.php";
//var LOCK_STATUS = "lockStatus.php";
//var USER_INFO = "userInfo.php";
//var ADMIN = "admin.php";
//
var makeid = function() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for( var i = 0; i < 103; i++ ) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

var Common = {
    'API_URL' : 'http://m.localhost/',
    'LOGIN' : 'login.php',
    'CHECK_LOGIN' : 'checklogin.php',
    'LOCK_STATUS' : 'lockStatus.php',
    'USER_INFO' : 'userInfo.php',
    'ADMIN' : 'admin.php',
    'madeid' : makeid
};

//module.exports = API_URL;
module.exports = Common;