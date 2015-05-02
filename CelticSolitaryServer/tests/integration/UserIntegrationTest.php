<?php // tests/integration/UserIntegrationTest.php

namespace TDW\Test\UserApi\Controller;

class UserIntegrationTest extends \LocalWebTestCase {

  const AUTH_PASS = TRUE;
  const AUTH_FAIL = FALSE;

  const TEST_GROUPNAME = 'TestGroup*';
  const TEST_USERNAME  = 'TestUser**';
  
  private static $em = NULL;  /** Entity Manager */

  private function setAuthenticationMock($response) {
    $auth = $this->getMock('TDW\UserApi\Controller\Auth');
    $auth->expects($this->any())->method('authenticate')->will($this->returnValue($response));
    $this->app->authentication = function ($c) use ($auth) {
        return $auth;
    };
  }

  private function getEM() {
    if (self::$em === NULL) {
      self::$em = GetEntityManager();
    }
    return self::$em;
  }

  /**
 * GET /group - Get the user list
 */
  public function testGetUsers() {
    self::$em = $this->getEM();
    $users = self::$em->getRepository('TDW\UserApi\Entity\User')->findAll();
    $expected = json_encode($users);
    $this->client->get(SLIM_APP_ROUTE . '/user');
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    $this->assertJsonStringEqualsJsonString($expected, $this->client->response->body());
  }

/**
 * POST /user - Creates a new user
 */
  public function testPostUserAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $userData = json_encode(array(
      'username' => self::TEST_USERNAME . rand(),
      'email'    => self::TEST_USERNAME . rand(),
      'password' => self::TEST_USERNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/user', $userData);
    $this->assertSame(403, $this->client->response->status());  // 403 - Forbidden    
  }

  public function testPostUserFailureGets400BadRequest() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $wrongUserData1 = json_encode(array(
      // 'username' => self::TEST_GROUPNAME . rand(),
      'email'    => self::TEST_USERNAME . rand(),
      'password' => self::TEST_USERNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/user', $wrongUserData1);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request

    $wrongUserData2 = json_encode(array(
      'username' => self::TEST_USERNAME . rand(),
      // 'email'    => self::TEST_USERNAME . rand(),
      'password' => self::TEST_USERNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/user', $wrongUserData2);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request

    $wrongUserData3 = json_encode(array(
      'username' => self::TEST_USERNAME . rand(),
      'email'    => self::TEST_USERNAME . rand(),
      // 'password' => self::TEST_USERNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/user', $wrongUserData3);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request

    self::$em = $this->getEM();
    $user1 = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneBy(array());
    $wrongUserData4 = json_encode(array(
      'username' => $user1->getUsername(),             // User already exist
      'email'    => self::TEST_USERNAME . rand(),
      'password' => self::TEST_USERNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/user', $wrongUserData4);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request

    $user2 = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneBy(array());
    $wrongUserData5 = json_encode(array(
      'username' => self::TEST_USERNAME . rand(),
      'email'    => $user2->getEmail(),                // Email already exist
      'password' => self::TEST_USERNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/user', $wrongUserData5);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request
  }

  public function testPostUserOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $userData = array(
      'username' => self::TEST_USERNAME . rand(),
      'email'    => self::TEST_USERNAME . rand(),
      'password' => self::TEST_USERNAME
      );
    $this->client->post(SLIM_APP_ROUTE . '/user', json_encode($userData));
    $this->assertSame(201, $this->client->response->status());  // 201 - Created    
    $this->assertEquals('application/json', $this->client->response['Content-Type']);

    self::$em = $this->getEM();
    $user = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneByUsername($userData['username']);
    $this->assertAttributeEquals($user->getId(), 'id', json_decode($this->client->response->body()));
    $this->assertAttributeEquals($user->getEmail(), 'email', json_decode($this->client->response->body()));
    
    self::$em->remove($user);
    self::$em->flush();
  }

/**
 * GET /user/{id} - Gets the user identified by ID
 */
  public function testGetUserByIdFailureGets404NotFound() {
    $this->client->get(SLIM_APP_ROUTE . '/user/0');
    $this->assertSame(404, $this->client->response->status()); // Not Found
  }

  public function testGetUserByIdOk() {
    self::$em = $this->getEM();
    $user = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneBy(array());
    $this->client->get(SLIM_APP_ROUTE . '/user/' . $user->getId());
    $expected = json_encode($user);
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    $this->assertJsonStringEqualsJsonString($expected, $this->client->response->body());
    
    $data = json_decode($this->client->response->body());
    $this->assertObjectHasAttribute('id', $data);
    $this->assertObjectHasAttribute('username', $data);
    $this->assertObjectHasAttribute('email', $data);
    $this->assertSame($user->getId(), $data->id);
    $this->assertSame(utf8_encode($user->getUsername()), $data->username);
    $this->assertSame(utf8_encode($user->getEmail()), $data->email);
  }

/**
 * DELETE /user/{id} - Deletes a user
 */
  public function testDeleteUserAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $this->client->delete(SLIM_APP_ROUTE . '/user/0');
    $this->assertSame(403, $this->client->response->status()); // Forbidden
  }

  public function testDeleteUserFailureGets404NotFound() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $this->client->delete(SLIM_APP_ROUTE . '/user/0');
    $this->assertSame(404, $this->client->response->status()); // Not Found
  }

  public function testDeleteUserAuthenticationOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    self::$em = $this->getEM();
    $user = new \TDW\UserApi\Entity\User();
    $user->setUsername(self::TEST_USERNAME . rand());
    $user->setEmail(self::TEST_USERNAME . rand());
    $user->setPassword(self::TEST_USERNAME);
    self::$em->persist($user);
    self::$em->flush();
    $this->client->delete(SLIM_APP_ROUTE . '/user/' . $user->getId());
    $this->assertSame(204, $this->client->response->status()); // No Content
  }

/**
 * PUT /user/{id} - Updates a user
 */
  public function testPutUserAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $this->client->put(SLIM_APP_ROUTE . '/user/1');
    $this->assertSame(403, $this->client->response->status()); // Forbidden
  }

  public function testPutUserFailureGets400BadRequest() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $this->client->put(SLIM_APP_ROUTE . '/user/0', 
              array(), array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(400, $this->client->response->status()); // Bad Request

    // Username already exists
    self::$em = $this->getEM();
    $user = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneBy(array());
    $wrongUserData1 = array(
      'username' => $user->getUsername()
      );
    $this->client->put(SLIM_APP_ROUTE . '/user/' . $user->getId(), 
              $wrongUserData1, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(400, $this->client->response->status()); // Bad Request

    // E-mail already exists
    $wrongUserData2 = array(
      'email' => $user->getEmail()
      );
    $this->client->put(SLIM_APP_ROUTE . '/user/' . $user->getId(), 
              $wrongUserData2, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(400, $this->client->response->status()); // Bad Request
  }

  public function testPutUserFailureGets404NotFound() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $userData = array(
      'username' => self::TEST_USERNAME . rand(),
      'email'    => self::TEST_USERNAME . rand()
      );
    $this->client->put(SLIM_APP_ROUTE . '/user/0', 
              $userData, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(404, $this->client->response->status()); // Not found
  }

  public function testPutUserOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    self::$em = $this->getEM();
    $userData = array(
      'username' => self::TEST_USERNAME . rand(),
      'email'    => self::TEST_USERNAME . rand(),
      'password' => self::TEST_USERNAME . rand(),
      'isActive' => rand() % 2,
      'isAdmin'  => rand() % 2
      );
    $user = new \TDW\UserApi\Entity\User();
    $user->setUsername(self::TEST_USERNAME . rand());
    $user->setEmail(self::TEST_USERNAME . rand());
    $user->setPassword($userData['password']);
    self::$em->persist($user);
    self::$em->flush();

    $this->client->put(SLIM_APP_ROUTE . '/user/' . $user->getId(),
              $userData, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(204, $this->client->response->status());  // 204, 'No Content'
    
    self::$em->remove($user);
    self::$em->flush();
  }

/**
 * GET /user/username/{username} - Gets the user identified by username
 */
  public function testGetUserByUsernameFailureGets404NotFound() {
    $this->client->get(SLIM_APP_ROUTE . '/user/username/ThisGroupNotExist');
    $this->assertSame(404, $this->client->response->status()); // Not Found
  }

  public function testGetUserByUsernameOk() {
    self::$em = $this->getEM();
    $user = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneBy(array());
    $this->client->get(SLIM_APP_ROUTE . '/user/username/' . $user->getUsername());
    $expected = json_encode($user);
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    $this->assertJsonStringEqualsJsonString($expected, $this->client->response->body());

    $data = json_decode($this->client->response->body());
    $this->assertObjectHasAttribute('id', $data);
    $this->assertObjectHasAttribute('username', $data);
    $this->assertObjectHasAttribute('email', $data);
    $this->assertSame($user->getId(), $data->id);
    $this->assertSame(utf8_encode($user->getUsername()), $data->username);
    $this->assertSame(utf8_encode($user->getEmail()), $data->email);
  }

/**
 * POST /group/user/{groupId}/{userId} - Adds user as member of group
 */
  public function testPostGroupUserAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $this->client->post(SLIM_APP_ROUTE . '/group/user/0/0');
    $this->assertSame(403, $this->client->response->status()); // Forbidden
  }

  public function testPostGroupUserFailureGets404NotFound() {
    self::$em = $this->getEM();
    $user = self::$em->getRepository('TDW\UserApi\Entity\User')->findOneBy(array());
    $this->setAuthenticationMock(self::AUTH_PASS);
    $this->client->post(SLIM_APP_ROUTE . '/group/user/0/' . $user->getId());
    $this->assertSame(404, $this->client->response->status());  // 404 - The group could not be found.
  }

  public function testPostGroupUserFailureGets400BadRequest() {
    self::$em = $this->getEM();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneBy(array());
    $this->setAuthenticationMock(self::AUTH_PASS);
    $this->client->post(SLIM_APP_ROUTE . '/group/user/' . $group->getId() . '/0');
    $this->assertSame(400, $this->client->response->status());  // 400 - User could not be found.
  }

  public function testPostGroupUserFailureGets409Conflict() {
    self::$em = $this->getEM();
    $qb = self::$em->createQueryBuilder();
    $qb->select('u')->from('TDW\UserApi\Entity\User', 'u')->where('u.group != 0')->setMaxResults(1);
    $user = $qb->getQuery()->getSingleResult();
    $this->setAuthenticationMock(self::AUTH_PASS);
    $this->client->post(SLIM_APP_ROUTE . '/group/user/' . $user->getGroup()->getId() . '/' . $user->getId());
    $this->assertSame(409, $this->client->response->status());  // 409 - User is already member of group.
  }

  public function testPostGroupUserOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    self::$em = $this->getEM();
    $user = new \TDW\UserApi\Entity\User();
    $user->setUsername(self::TEST_USERNAME . rand());
    $user->setEmail(self::TEST_USERNAME . rand() . '@example.com');
    $user->setPassword(self::TEST_USERNAME . rand());
    self::$em->persist($user);
    self::$em->flush();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneBy(array());
    $this->client->post(SLIM_APP_ROUTE . '/group/user/' . $group->getId() . '/' . $user->getId());
    $this->assertSame(201, $this->client->response->status());  // 201 - Created

    self::$em->remove($user);
    self::$em->flush();
  }
}