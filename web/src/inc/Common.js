var API_URL = "http://m.localhost/";
//var LOGIN = "login.php";
//var CHECK_LOGIN = "checklogin.php";
//var LOCK_STATUS = "lockStatus.php";
//var USER_INFO = "userInfo.php";
//var ADMIN = "admin.php";
//
var makeid = function () {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for (var i = 0; i < 103; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

var headers = {'X-DoorLock-Api-Key': "test"};

var Common = {
  //'API_URL' : 'http://m.localhost/',
  //'API_KEY': "X-DoorLock-Api-Key",
  //'API_KEY_VALUE': "test",
  'API_URL': 'http://api.localhost/',
  'LOGIN': 'login.php',
  'CHECK_LOGIN': 'checklogin.php',
  'LOCK_STATUS': 'LockStatus',
  'USER_INFO': 'GetUserInfo',
  'ADMIN': 'GetAllUsers',
  //'ADMIN' : 'admin.php',
  HEADERS: headers,
  'makeid': makeid
};

//module.exports = API_URL;
module.exports = Common;