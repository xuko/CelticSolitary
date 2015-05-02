<?php // tests/unit/HTTP_StatusTest.php

namespace TDW\Test\UserApi\Controller;

use TDW\UserApi\Controller\HTTP_Status;

class HTTP_StatusTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var HTTP_Status
   */
  protected $status;
  
  const HTTP_STATUS_CODE    = PHP_VERSION_ID;
  const HTTP_STATUS_MESSAGE = PHP_VERSION;

  /**
   * Sets up the fixture.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->status = new HTTP_Status(self::HTTP_STATUS_CODE, self::HTTP_STATUS_MESSAGE);
  }

  /**
   * Tears down the fixture.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * Implement testConstructor
   * 
   * @covers TDW\UserApi\Controller\HTTP_Status::__construct
   * @covers TDW\UserApi\Controller\HTTP_Status::getCode
   * @covers TDW\UserApi\Controller\HTTP_Status::getMessage
   */
  public function testConstructor() {
    $this->status = new HTTP_Status(self::HTTP_STATUS_CODE, self::HTTP_STATUS_MESSAGE);
    $this->assertSame(self::HTTP_STATUS_CODE, $this->status->getCode());
    $this->assertSame(self::HTTP_STATUS_MESSAGE, $this->status->getMessage());
  }

  /**
   * Implement testGetCode().
   * 
   * @covers TDW\UserApi\Controller\HTTP_Status::setCode
   * @covers TDW\UserApi\Controller\HTTP_Status::getCode
   */
  public function testCode() {
    $this->assertSame(self::HTTP_STATUS_CODE, $this->status->getCode());
    $this->status->setCode(2015);
    $this->assertSame(2015, $this->status->getCode());
  }

  /**
   * Implement testMessage().
   * 
   * @covers TDW\UserApi\Controller\HTTP_Status::setMessage
   * @covers TDW\UserApi\Controller\HTTP_Status::getMessage
   */
  public function testMessage() {
    $this->assertSame(self::HTTP_STATUS_MESSAGE, $this->status->getMessage());
    $this->status->setMessage('T.D.W.');
    $this->assertSame('T.D.W.', $this->status->getMessage());
  }

  /**
   * Implement testJsonSerialize().
   * 
   * @covers TDW\UserApi\Controller\HTTP_Status::jsonSerialize
   */
  public function testJsonSerialize() {
    $json = $this->status->jsonSerialize();
    $this->assertJson(json_encode($json));
    $this->assertArrayHasKey('code', $json);
    $this->assertArrayHasKey('message', $json);
  }
}
