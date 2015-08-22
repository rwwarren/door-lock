<?php

//include_once("../web/inc/dbcon.php");
$root = realpath(dirname(__FILE__));
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once("$root/../src/inc/dbcon.php");
//include "../web/inc/variables..php";

class dbconnTest extends PHPUnit_Framework_TestCase {
//class dbconTest extends PHPUnit_Framework_TestCase {

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
        $this->assertTrue($conn !== null);
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
        $results = $conn->login('test', 'password');
        //$results = $conn->login('test', 'test');
        //unserialize($results);
        //$this->assertTrue($results !== false);
        //$this->assertContains('HTTP/1.0 403 Error Username or Password incorrect', $results);
        //include_once("/web/inc/dbcon.php");
    }
    /**
     * @test
     */
    public function invalidLogin() {
        $conn = new dbconn("read");
        $results = $conn->login('test', 'invalidpassword');
        $this->assertFalse($results);
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
        $conn = new dbconn("read");
        //$invalidAuthy = $conn->checkAuthy("test", "asdf");
        //$this->assertFalse($invalidAuthy);
        //
    }

    /**
     * @test
     */
    public function getUsers() {
        //
        $conn = new dbconn("read");
        $allUsers = $conn->getUsers();
        $this->assertTrue($allUsers !== null);
    }

    /**
     * @test
     */
    public function getActiveUsers() {
        //
        $conn = new dbconn("read");
        $allUsers = $conn->getActiveUsers();
        $this->assertTrue($allUsers !== null);
    }

    /**
     * @test
     */
    public function getInactiveUsers() {
        //
        $conn = new dbconn("read");
        $allUsers = $conn->getInactiveUsers();
        $this->assertTrue($allUsers !== null);
    }

    /**
     * @test
     */
    public function getAdmins() {
        //
        $conn = new dbconn("read");
        $allUsers = $conn->getAdmins();
        $this->assertTrue($allUsers !== null);
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
