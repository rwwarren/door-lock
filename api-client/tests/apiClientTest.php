<?php
$root = realpath(dirname(__FILE__));
include_once("$root/../src/root/apiClient.php");

class apiClientTest extends PHPUnit_Framework_TestCase {

  /**
   * @test
   */
  public function testLogin(){
    $client = new ApiClient;
    $this->assertTrue($client !== null);
    $this->assertTrue($client->login() !== null);

  }

}
