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
    $conn = new dbconn;
    //$conn->login('test', 'password');
    //include_once("/web/inc/dbcon.php");
    // Arrange
    //$a = new Money(1);

    // Act
    //$b = $a->negate();

    // Assert
    //$this->assertEquals(-1, $b->getAmount());
  }

}
