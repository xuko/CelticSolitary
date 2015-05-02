<?php // tests/integration/GroupIntegrationTest.php

namespace TDW\Test\UserApi\Controller;

class GroupIntegrationTest extends \LocalWebTestCase {

  const AUTH_PASS = TRUE;
  const AUTH_FAIL = FALSE;

  const TEST_GROUPNAME = 'TestGroup*';
  const TEST_USERNAME  = 'TestUser**';

  /** Entity Manager */
  private static $em = NULL;

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
   * GET /group - Get the group list
   */
  public function testGetGroups() {
    self::$em = $this->getEM();
    $groups = self::$em->getRepository('TDW\UserApi\Entity\Group')->findAll();
    $expected = json_encode($groups);
    $this->client->get(SLIM_APP_ROUTE . '/group');
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    $this->assertJsonStringEqualsJsonString($expected, $this->client->response->body());
  }

  /**
   * POST /group - Creates a new group
   */
  public function testPostGroupAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $groupData = json_encode(array(
      'groupname'   => self::TEST_GROUPNAME,
      'description' => 'description ' . self::TEST_GROUPNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/group', $groupData);
    $this->assertSame(403, $this->client->response->status());  // 403 - Forbidden    
  }

  public function testPostGroupFailureGets400BadRequest() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $wrongGroupData1 = json_encode(array(
      'description' => 'description ' . self::TEST_GROUPNAME
      ));
    $this->client->post(SLIM_APP_ROUTE . '/group', $wrongGroupData1);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request
    
    self::$em = $this->getEM();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneBy(array());
    $wrongGroupData2 = json_encode(array(
      'groupname'   => $group->getGroupname(),    // Group already exists
      'description' => 'description ' . self::TEST_GROUPNAME 
      ));
    $this->client->post(SLIM_APP_ROUTE . '/group', $wrongGroupData2);
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request
  }

  public function testPostGroupOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $groupData = array(
      'groupname'   => self::TEST_GROUPNAME . rand(),
      'description' => 'description ' . self::TEST_GROUPNAME
      );
    $this->client->post(SLIM_APP_ROUTE . '/group', json_encode($groupData));
    $this->assertSame(201, $this->client->response->status());  // 201 - Created    
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    self::$em = $this->getEM();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneByGroupname($groupData['groupname']);
    $groupData['id'] = $group->getId();
    $this->assertAttributeEquals($group->getId(), 'id', json_decode($this->client->response->body()));
    
    self::$em->remove($group);
    self::$em->flush();
  }

  /**
   * GET /group/{id} - Gets the group identified by ID
   */
  public function testGetGroupByIdFailureGets404NotFound() {
    $this->client->get(SLIM_APP_ROUTE . '/group/0');
    $this->assertSame(404, $this->client->response->status()); // Not Found
  }

  public function testGetGroupByIdOk() {
    self::$em = $this->getEM();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneBy(array());
    $this->client->get(SLIM_APP_ROUTE . '/group/' . $group->getId());
    $expected = json_encode($group);
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    $this->assertJsonStringEqualsJsonString($expected, $this->client->response->body());
    
    $data = json_decode($this->client->response->body());
    $this->assertObjectHasAttribute('id', $data);
    $this->assertObjectHasAttribute('groupname', $data);
    $this->assertObjectHasAttribute('description', $data);
    $this->assertSame($group->getId(), $data->id);
    $this->assertSame(utf8_encode($group->getGroupname()), $data->groupname);
    $this->assertSame(utf8_encode($group->getDescription()), $data->description);
  }

  /**
   * DELETE /group/{id} - Deletes a group
   */
  public function testDeleteGroupAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $this->client->delete(SLIM_APP_ROUTE . '/group/1');
    $this->assertSame(403, $this->client->response->status()); // Forbidden
  }

  public function testDeleteGroupFailureGets404NotFound() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $this->client->delete(SLIM_APP_ROUTE . '/group/0');
    $this->assertSame(404, $this->client->response->status()); // Not Found
  }

  public function testDeleteGroupOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    self::$em = $this->getEM();
    $group = new \TDW\UserApi\Entity\Group(self::TEST_GROUPNAME . rand());
    self::$em->persist($group);
    self::$em->flush();
    $this->client->delete(SLIM_APP_ROUTE . '/group/' . $group->getId());
    $this->assertSame(204, $this->client->response->status()); // No Content
  }

/**
 * PUT /group/{id} - Updates a group
 */
  public function testPutGroupAuthenticationFailureGets403Forbidden() {
    $this->setAuthenticationMock(self::AUTH_FAIL);
    $this->client->put(SLIM_APP_ROUTE . '/group/1');
    $this->assertSame(403, $this->client->response->status()); // Forbidden
  }

  public function testPutGroupFailureGets404NotFound() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    $GroupData1 = array(
      'groupname'   => self::TEST_GROUPNAME . rand(),
      'description' => 'description ' . self::TEST_GROUPNAME
      );
    $this->client->put(SLIM_APP_ROUTE . '/group/0', 
              $GroupData1, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(404, $this->client->response->status(), http_build_query($GroupData1)); // Not Found
  }

  public function testPutGroupFailureGets400BadRequest() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    self::$em = $this->getEM();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneBy(array());

    $wrongGroupData1 = array(
      'groupname'   => NULL,
      'description' => NULL
      );
    $this->client->put(SLIM_APP_ROUTE . '/group/' . $group->getId(),
              $wrongGroupData1, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request
    
    $wrongGroupData2 = array(
      'groupname'   => $group->getGroupname(),    // Group already exists
      'description' => 'description ' . self::TEST_GROUPNAME 
      );
    $this->client->put(SLIM_APP_ROUTE . '/group/' . $group->getId(),
              $wrongGroupData2, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(400, $this->client->response->status());  // 400 - Bad Request
  }

  public function testPutGroupOk() {
    $this->setAuthenticationMock(self::AUTH_PASS);
    self::$em = $this->getEM();
    $groupData = array(
      'groupname'   => self::TEST_GROUPNAME . rand(),
      'description' => 'description ' . self::TEST_GROUPNAME
      );
    $group = new \TDW\UserApi\Entity\Group($groupData['groupname']);
    self::$em->persist($group);
    self::$em->flush();

    $groupData['groupname'] = NULL;
    $this->client->put(SLIM_APP_ROUTE . '/group/' . $group->getId(),
              $groupData, array('CONTENT_TYPE' => 'application/x-www-form-urlencoded'));
    $this->assertSame(204, $this->client->response->status());  // 204, 'No Content'
    
    self::$em->remove($group);
    self::$em->flush();
  }

/**
 * GET /group/groupname/{groupname} - Gets the group identified by groupname
 */
  public function testGetGroupByGroupnameFailureGets404NotFound() {
    $this->client->get(SLIM_APP_ROUTE . '/group/groupname/ThisGroupNotExist');
    $this->assertSame(404, $this->client->response->status()); // Not Found
  }

  public function testGetGroupByGroupnameOk() {
    self::$em = $this->getEM();
    $group = self::$em->getRepository('TDW\UserApi\Entity\Group')->findOneBy(array());
    $this->client->get(SLIM_APP_ROUTE . '/group/groupname/' . $group->getGroupname());
    $expected = json_encode($group);
    $this->assertEquals(200, $this->client->response->status());
    $this->assertEquals('application/json', $this->client->response['Content-Type']);
    $this->assertJsonStringEqualsJsonString($expected, $this->client->response->body());

    $data = json_decode($this->client->response->body());
    $this->assertObjectHasAttribute('id', $data);
    $this->assertObjectHasAttribute('groupname', $data);
    $this->assertObjectHasAttribute('description', $data);
    $this->assertSame($group->getId(), $data->id);
    $this->assertSame(utf8_encode($group->getGroupname()), $data->groupname);
    $this->assertSame(utf8_encode($group->getDescription()), $data->description);
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