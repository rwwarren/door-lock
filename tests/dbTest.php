<?php
//include_once("../web/inc/dbcon.php");
include_once("web/inc/dbcon.php");
//include "../web/inc/variables..php";

class DBTest extends PHPUnit_Framework_TestCase {

  /*
   * @test
   */
  //public function DBLogin() {
  public function testDBLogin() {
    //ob_start();
    $conn = new dbconn("read");
    //$conn->connect("read");
    //$results = $conn->login('test', 'password');
    //$this->assertContains('HTTP/1.0 403 Error Username or Password incorrect', $results);
    //include_once("/web/inc/dbcon.php");
    // Arrange
    //$a = new Money(1);

    // Act
    //$b = $a->negate();

    // Assert
    //$this->assertEquals(-1, $b->getAmount());
  }

}
