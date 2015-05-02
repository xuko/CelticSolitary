<?php // tests/unit/Entity/UserTest.php

namespace TDW\Test\UserApi\Entity;

use TDW\UserApi\Entity\User,
    TDW\UserApi\Entity\Group;

class UserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var TDW\UserApi\Entity\User
   */
  protected $user;

  const USER_NAME = 'user ñ¿?Ñ';
  const USER_EMAIL = 'userEmail@example.com';
  const USER_PASSWORD = '$%&/¿?abcABC123€';
  const USER_NOTE = '€¬@#| \\ $%&/¿?abcABC123€';

  /**
   * Sets up the fixture.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->user = new User();
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
   * @covers TDW\UserApi\Entity\User::__construct
   * @covers TDW\UserApi\Entity\User::getId
   * @covers TDW\UserApi\Entity\User::getUsername
   * @covers TDW\UserApi\Entity\User::getIsAdmin
   * @covers TDW\UserApi\Entity\User::getIsActive
   */
  public function testConstructor() {
    $this->user = new User();
    $this->assertEmpty($this->user->getId());
    $this->assertEmpty($this->user->getUsername());
    $this->assertFalse($this->user->getIsAdmin());
    $this->assertTrue($this->user->getIsActive());
  }

  /**
   * Implement testGetId().
   * 
   * @covers TDW\UserApi\Entity\User::getId
   */
  public function testGetId() {
    $this->assertEmpty($this->user->getId());
  }

  /**
   * Implement testUsername().
   * 
   * @covers TDW\UserApi\Entity\User::setUsername
   * @covers TDW\UserApi\Entity\User::getUsername
   */
  public function testUsername() {
    $this->user->setUsername(self::USER_NAME);
    $this->assertSame(
            self::USER_NAME, $this->user->getUsername()
    );
  }

  /**
   * Implement testPassword().
   * 
   * @covers TDW\UserApi\Entity\User::setPassword
   * @covers TDW\UserApi\Entity\User::getPassword
   * @covers TDW\UserApi\Entity\User::validatePassword
   */
  public function testPassword() {
    $this->user->setPassword(self::USER_PASSWORD);
    $this->assertTrue($this->user->validatePassword(self::USER_PASSWORD));
    $this->assertTrue(password_verify(self::USER_PASSWORD, $this->user->getPassword()));
  }

  /**
   * Implement testIsAdmin().
   * 
   * @covers TDW\UserApi\Entity\User::setIsAdmin
   * @covers TDW\UserApi\Entity\User::getIsAdmin
   */
  public function testIsAdmin() {
    $this->user->setIsAdmin(TRUE);
    $this->assertTrue($this->user->getIsAdmin());
    $this->user->setIsAdmin(FALSE);
    $this->assertFalse($this->user->getIsAdmin());
  }

  /**
   * Implement testIsActive().
   * 
   * @covers TDW\UserApi\Entity\User::setIsActive
   * @covers TDW\UserApi\Entity\User::getIsActive
   */
  public function testIsActive() {
    $this->user->setIsActive(TRUE);
    $this->assertTrue($this->user->getIsActive());
    $this->user->setIsActive(FALSE);
    $this->assertFalse($this->user->getIsActive());
  }

  /**
   * Implement testGroup().
   * 
   * @covers TDW\UserApi\Entity\User::setGroup
   * @covers TDW\UserApi\Entity\User::getGroup
   */
  public function testGroup() {
    $grupo = new Group('testGroup');
    $this->user->setGroup($grupo);
    $this->assertSame(
            $grupo, $this->user->getGroup()
            );
  }

  /**
   * Implement testEmail().
   * 
   * @covers TDW\UserApi\Entity\User::setEmail
   * @covers TDW\UserApi\Entity\User::getEmail
   */
  public function testEmail() {
    $this->user->setEmail(self::USER_EMAIL);
    $this->assertSame(
            self::USER_EMAIL, $this->user->getEmail()
            );
  }

  /**
   * Implement testNote().
   * 
   * @covers TDW\UserApi\Entity\User::setNote
   * @covers TDW\UserApi\Entity\User::getNote
   */
  public function testNote() {
    $this->user->setNote(self::USER_NOTE);
    $this->assertSame(
            self::USER_NOTE, $this->user->getNote()
            );
  }

  /**
   * Implement testCreateTime().
   * 
   * @covers TDW\UserApi\Entity\User::setCreateTime
   * @covers TDW\UserApi\Entity\User::getCreateTime
   */
  public function testCreateTime() {
    $this->user->setCreateTime(new \DateTime());
    $this->assertNotEmpty($this->user->getCreateTime());
  }

  /**
   * Implement testSerialize().
   * 
   * @covers TDW\UserApi\Entity\User::serialize
   * @covers TDW\UserApi\Entity\User::unserialize
   */
  public function testSerialize() {
    $this->user->setUsername(self::USER_NAME);
    $this->user->setEmail(self::USER_EMAIL);
    $cadena = $this->user->serialize();
    $this->user->unserialize($cadena);

    $this->assertSame(
            self::USER_NAME, $this->user->getUsername()
            );
    $this->assertSame(
            self::USER_EMAIL, $this->user->getEmail()
            );
  }

  /**
   * Implement testToString().
   * 
   * @covers TDW\UserApi\Entity\User::__toString
   */
  public function testToString() {
    $this->user->setUsername(self::USER_NAME);
    $this->assertSame(
            self::USER_NAME, $this->user->__toString()
    );
  }

  /**
   * Implement testToString().
   * 
   * @covers TDW\UserApi\Entity\User::jsonSerialize
   */
  public function testJsonSerialize() {
    $json = $this->user->jsonSerialize();
    $this->assertJson(json_encode($json));
    $this->assertArrayHasKey('id', $json);
    $this->assertArrayHasKey('username', $json);
    $this->assertArrayHasKey('email', $json);
    $this->assertArrayHasKey('createTime', $json);
    $this->assertArrayHasKey('isActive', $json);
    $this->assertArrayHasKey('isAdmin', $json);
    $this->assertArrayHasKey('group_id', $json);
    $this->assertArrayHasKey('note', $json);
  }
}
