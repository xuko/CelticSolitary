<?php // tests/integration/LoginTest.php

namespace TDW\Test\UserApi\Controller;

class LoginTest extends \LocalWebTestCase {

  const TEST_USERNAME  = 'TestUser**';
  
  private static $em = NULL;  /** Entity Manager */

  private function getEM() {
    if (self::$em === NULL) {
      self::$em = GetEntityManager();
    }
    return self::$em;
  }

/**
 * POST /authentication - Authenticates a user
 */
  public function testPostAuthenticationFailureGets400BadRequest() {
    $wrongUserData1 = array(
        'username' => self::TEST_USERNAME,
        'password' => self::TEST_USERNAME
        );
    $this->client->post(SLIM_APP_ROUTE . '/authentication', http_build_query($wrongUserData1));
    $this->assertEquals(400, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);

    $wrongUserData2 = array(
        'username' => self::TEST_USERNAME,
        'password' => NULL
        );
    $this->client->post(SLIM_APP_ROUTE . '/authentication', http_build_query($wrongUserData2));
    $this->assertEquals(400, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
  }

  public function testPostAuthenticationOk() {
    self::$em = $this->getEM();
    $user = new \TDW\UserApi\Entity\User();
    $user->setUsername(self::TEST_USERNAME . rand());
    $user->setEmail(self::TEST_USERNAME . rand());
    $user->setPassword(self::TEST_USERNAME);
    self::$em->persist($user);
    self::$em->flush();

    $userData = array(
        'username' => $user->getUsername(),
        'password' => self::TEST_USERNAME
        );
    $this->client->post(SLIM_APP_ROUTE . '/authentication', http_build_query($userData));
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);

    self::$em->remove($user);
    self::$em->flush();
  }

/**
 * GET /login
 */
  public function testGetLoginOk() {
    $this->client->get(SLIM_APP_ROUTE . '/login');
    $this->assertSame(200, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
  }

/**
 * POST /login
 */
  public function testPostLoginFailure() {
    $wrongUserData1 = array(
        'username' => self::TEST_USERNAME,
        'password' => self::TEST_USERNAME
        );
    $this->client->post(SLIM_APP_ROUTE . '/login', http_build_query($wrongUserData1));
    $this->assertEquals(302, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
    $this->assertContains(SLIM_APP_ROUTE . '/login', $this->client->response->headers->get('Location'));

    self::$em = $this->getEM();
    $user = new \TDW\UserApi\Entity\User();
    $user->setUsername(self::TEST_USERNAME . rand());
    $user->setEmail(self::TEST_USERNAME . rand());
    $user->setPassword(self::TEST_USERNAME);
    self::$em->persist($user);
    self::$em->flush();

    $wrongUserData2 = array(
        'username' => $user->getUsername(),
        'password' => self::TEST_USERNAME . rand() // incorrect password
        );
    $this->client->post(SLIM_APP_ROUTE . '/login', http_build_query($wrongUserData2));
    $this->assertEquals(302, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
    $this->assertArrayNotHasKey('user', $_SESSION);

    $wrongUserData3 = array( // empty password
        'username' => $user->getUsername(),
        // 'password' => self::TEST_USERNAME . rand()
        );
    $this->client->post(SLIM_APP_ROUTE . '/login', http_build_query($wrongUserData3));
    $this->assertEquals(302, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
    $this->assertArrayNotHasKey('user', $_SESSION);

    self::$em->remove($user);
    self::$em->flush();
  }

  public function testPostLoginOk() {
    self::$em = $this->getEM();
    $user = new \TDW\UserApi\Entity\User();
    $user->setUsername(self::TEST_USERNAME . rand());
    $user->setEmail(self::TEST_USERNAME . rand());
    $user->setPassword(self::TEST_USERNAME);
    self::$em->persist($user);
    self::$em->flush();

    $userData = array(
        'username' => $user->getUsername(),
        'password' => self::TEST_USERNAME
        );
    $this->client->post(SLIM_APP_ROUTE . '/login', http_build_query($userData));
    $this->assertEquals(302, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
    $this->assertSame($user->getUsername(), $_SESSION['user']);

    $this->client->get($this->app->urlFor('tdw_logout'));
    $this->assertEquals(302, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);

    self::$em->remove($user);
    self::$em->flush();
  }

/**
 * GET, POST /logout
 */
  public function testGetLogoutOk() {
    $this->client->get($this->app->urlFor('tdw_logout'));
    $this->assertSame(200, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
  }

  public function testPostLogoutOk() {
    $this->client->post($this->app->urlFor('tdw_logout'));
    $this->assertSame(200, $this->client->response->status());
    $this->assertEquals('text/html', $this->client->response['Content-Type']);
  }

}
