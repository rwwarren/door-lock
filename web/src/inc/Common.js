'use strict';

var makesid = function() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for(var i = 0; i < 103; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

var Common = {
  'API_URL': 'http://localhost:1337/api.localhost/',
  'LOGIN': 'login',
  'LOGOUT': 'logout',
  'CHECK_LOGIN': 'IsLoggedIn',
  'LOCK_STATUS': 'LockStatus',
  'USER_INFO': 'GetUserInfo',
  'ADMIN': 'GetAllUsers',
  'CONFIG': 'config',
  'makeid': makesid
};

module.exports = Common;
