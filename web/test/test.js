'use strict';
var assert = require("assert");
var common = require('../src/inc/Common');

describe('Constants Testing', function() {
  describe('Common File Testing', function () {
    it('should return Admin constant properly', function () {
      assert.equal(common.ADMIN, "GetAllUsers");
    });
    it('should return logout constant properly', function () {
      assert.equal(common.LOGOUT, "logout");
    });
    it('should return login constant properly', function () {
      assert.equal(common.LOGIN, "login");
    });
    it('should return logged in constant properly', function () {
      assert.equal(common.CHECK_LOGIN, "IsLoggedIn");
    });
    it('should return lock status constant properly', function () {
      assert.equal(common.LOCK_STATUS, "LockStatus");
    });
    it('should return user info constant properly', function () {
      assert.equal(common.USER_INFO, "GetUserInfo");
    });
  });
});

