'use strict';

var makeid = function() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for(var i = 0; i < 103; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

var Common = {
  'API_URL': 'http://api.localhost/',
  //'API_URL': 'http://api.localhost:8080/',
  'LOGIN': 'login',
  'LOGOUT': 'logout',
  'CHECK_LOGIN': 'IsLoggedIn',
  'LOCK_STATUS': 'LockStatus',
  'USER_INFO': 'GetUserInfo',
  'ADMIN': 'GetAllUsers',
  'CONFIG': 'config',
  'makeid': makeid
};

module.exports = Common;