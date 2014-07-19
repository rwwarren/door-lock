<?php
//include_once("../web/inc/dbcon.php");
include_once("web/inc/dbcon.php");
//include "../web/inc/variables..php";

class DBTest extends PHPUnit_Framework_TestCase {

  /**
   * @test
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage Database user not selected properly
   */
  public function createInvalidDBObject() {
    $conn = new dbconn("reader");
  }

  /**
   * @test
   */
  public function createValidDBObject() {
    $conn = new dbconn("read");
  }

  /**
   * @test
   */
  public function deleteValidDBObject() {
    $conn = new dbconn("read");
    unset($conn);
    $this->assertTrue(!isset($conn));
  }

  /**
   * @test
   */
  public function validLogin() {
    //ob_start();
    $conn = new dbconn("read");
    //$conn->connect("read");
    //$results = $conn->login('test', 'password');
    //$this->assertContains('HTTP/1.0 403 Error Username or Password incorrect', $results);
    //include_once("/web/inc/dbcon.php");
  }
  /**
   * @test
   */
  public function invalidLogin() {
    $conn = new dbconn("read");
  }

  /**
   * @test
   */
  public function validCheckAuthy() {
    //
  }

  /**
   * @test
   */
  public function invalidCheckAuthy() {
    //
  }

  /**
   * @test
   */
  public function getUsers() {
    //
  }

  /**
   * @test
   */
  public function getActiveUsers() {
    //
  }

  /**
   * @test
   */
  public function getInactiveUsers() {
    //
  }

  /**
   * @test
   */
  public function getAdmins() {
    //
  }

  /**
   * @test
   */
  public function validChangePassword() {
    //
  }

  /**
   * @test
   */
  public function invalidChangePassword() {
    //
  }

  /**
   * @test
   */
  public function updateUserInfo() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function checkPassword() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function resetChangePassword() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function registerUser() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function removeUser() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function changeUser() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function resetPassword() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function findResetToken() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function findUser() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function resetPasswordQuery() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function invalidateResetURL() {
    //TODO valid and invalid testing
    //
  }

  /**
   * @test
   */
  public function getUserInfo() {
    //TODO valid and invalid testing
    //
  }

}
