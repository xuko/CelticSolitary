<?php // tests/unit/AuthTest.php

namespace TDW\Test\UserApi\Controller;

use TDW\UserApi\Controller\Auth;

class AuthTest extends \PHPUnit_Framework_TestCase {

  public function testAuthenticate() {
    $myAuth = new Auth();
    $this->assertFalse($myAuth->authenticate());
  }
}
