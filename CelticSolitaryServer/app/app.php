<?php // app/app.php

namespace TDW\UserApi\Controller;

require_once __DIR__ . '/../config/bootstrap.php';

use TDW\UserApi\Entity\Group,
    TDW\UserApi\Entity\User,
    Monolog\Handler\RotatingFileHandler,
    Monolog\Formatter\LineFormatter,
    Monolog\Logger;

// Dependency Injection Containers
// -----------------------------------------------------------------------------
// In our unit tests, we'll mock these so that we can control our application
// state.
$app->authentication = function () {
  return new Auth();
};

// Authentication Middleware
// -----------------------------------------------------------------------------
$authenticate = function ($app) {
  return function () use ($app) {
    $auth = $app->authentication;
    if ($auth->authenticate()) {
      return;
    }
    $app->outputData(new HTTP_Status(403, 'Forbidden'));
    $app->stop();
  };
};

// Welcome Page: TDW User Api
// -----------------------------------------------------------------------------
$app->get('/', function () use ($app) {
  $app->redirect(SLIM_API_ROUTE);  // 302 Found
});

/**
 * TDW_UserApi /group routes
 */
# ###########################
// GET /group - Get the group list
$app->get(SLIM_APP_ROUTE . '/group', function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $em = GetEntityManager();
  $groups = $em->getRepository('TDW\UserApi\Entity\Group')->findAll();
  $app->outputData(
          empty($groups)
          ? new HTTP_Status(404, 'Not Found')
          : $groups
        );  
})->name('tdw_get_groups');

// POST /group - Creates a new group
$app->post(SLIM_APP_ROUTE . '/group', $authenticate($app), function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $data = json_decode($app->request->getBody());
  if (!isset($data->groupname)) {
    $app->outputData(new HTTP_Status(400, 'Bad Request'));      
  } else {
    $em = GetEntityManager();
    $data->description = (isset($data->description) ? $data->description : '');
    $groupRepository = $em->getRepository('TDW\UserApi\Entity\Group');
    $groups = $groupRepository->findOneByGroupname(mb_convert_encoding($data->groupname, 'ISO-8859-1', 'UTF-8'));
    if (count($groups)) { // Group already exists
      $app->outputData(new HTTP_Status(400, 'Bad Request'));                
    } else {
      $group = new Group(mb_convert_encoding($data->groupname, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($data->description, 'ISO-8859-1', 'UTF-8'));
      $em->persist($group);
      $em->flush();
      if (!empty($group)) {
        // $app->log->addInfo(SLIM_APP_ROUTE . '/group', ['status' => 201]);
        $app->response->setStatus(201); // Created
        $app->outputData($group);
      }          
    }
  }
})->name('tdw_post_group');

// GET /group/{id} - Gets the group identified by ID
$app->get(SLIM_APP_ROUTE . '/group/:id', function ($id) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '\d+'))
  ->name('tdw_get_group_by_id');

// DELETE /group/{id} - Deletes a group
$app->delete(SLIM_APP_ROUTE . '/group/:id', $authenticate($app), function ($id) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '\d+'))
  ->name('tdw_delete_group');

// PUT /group/{id} - Updates a group
$app->put(SLIM_APP_ROUTE . '/group/:id', $authenticate($app), function ($id) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '\d+'))
  ->name('tdw_put_group');

// GET /group/groupname/{groupname} - Gets the group identified by groupname
$app->get(SLIM_APP_ROUTE . '/group/groupname/:groupname', function ($groupname) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '[[:alnum:]]'))
  ->name('tdw_get_group_by_groupname');

// POST /group/user/{groupId}/{userId} - Adds user as member of group
$app->post(SLIM_APP_ROUTE . '/group/user/:groupId/:userId', $authenticate($app),
        function ($groupId, $userId) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('groupId' => '\d+', 'userId' => '\d+'))
  ->name('tdw_post_group_user');

/**
 * TDW_UserApi /user routes
 */
# ###########################

// GET /user - Get the users list
$app->get(SLIM_APP_ROUTE . '/user', function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $em = GetEntityManager();
  $users = $em->getRepository('TDW\UserApi\Entity\User')->findAll();
  $app->outputData(
          empty($users)
          ? new HTTP_Status(204, 'No Content')
          : $users
        );
})->name('tdw_get_users');

// POST /user - Creates a new user
$app->post(SLIM_APP_ROUTE . '/user', $authenticate($app), function () use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->name('tdw_post_user');

// GET /user/{id} - Gets the user identified by ID
$app->get(SLIM_APP_ROUTE . '/user/:id', function ($id) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '\d+'))
  ->name('tdw_get_user_by_id');

// DELETE /user/{id} - Deletes a user
$app->delete(SLIM_APP_ROUTE . '/user/:id', $authenticate($app), function ($id) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '\d+'))
  ->name('tdw_delete_user');

// PUT /user/{id} - Updates a user
$app->put(SLIM_APP_ROUTE . '/user/:id', $authenticate($app), function ($id) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '\d+'))
  ->name('tdw_put_user');

// GET /user/username/{username} - Gets the user identified by username
$app->get(SLIM_APP_ROUTE . '/user/username/:username', function ($username) use ($app) {

  // TODO
  $app->outputData(new HTTP_Status(501, 'Not Implemented'));

})->conditions(array('id' => '[[:alnum:]]'))
  ->name('tdw_get_user_by_username');

/**
 * Path /TDW_UserApi/v1 - App additional routes (/authentication, /login, /logout)
 */

// POST /authentication - Authenticates a user
$app->post(SLIM_APP_ROUTE . '/authentication', function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $username = $app->request->post('username');
  $password = $app->request->post('password');

  if (empty($username) || empty($password)) {
    $app->outputData(new HTTP_Status(400, 'Bad Request'));
  } else {
    $em = GetEntityManager();
    $userRepository = $em->getRepository('TDW\UserApi\Entity\User');
    $user = $userRepository->findOneByUsername(mb_convert_encoding($username, 'ISO-8859-1', 'UTF-8'));
    $app->outputData(
            (empty($user) || !$user->validatePassword($password))
            ? new HTTP_Status(400, 'Bad Request')
            : new HTTP_Status(200, 'Ok')
          );
  }
})->name('tdw_authentication');

// GET /logout https://github.com/briannesbitt/Slim-ContextSensitiveLoginLogout
$app->map(SLIM_APP_ROUTE . '/logout', function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $app->log->addInfo('Logout');
  $_SESSION = array();
  unset($_SESSION['user'], $_SESSION['isAdmin']);
  $app->view()->setData('user', NULL);
  $app->render('logout.php');
})->via('GET', 'POST')
  ->name('tdw_logout'); // GET,POST /logout

// GET /login https://github.com/briannesbitt/Slim-ContextSensitiveLoginLogout
$app->get(SLIM_APP_ROUTE . '/login', function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $flash = $app->view()->getData('flash');
  $error = (isset($flash['error'])) ? $flash['error'] : NULL;
  $urlRedirect = '/';

  if (isset($_SESSION['urlRedirect'])) {
     $urlRedirect = $_SESSION['urlRedirect'];
  }

  $username_value = (isset($flash['username'])) ? $flash['username'] : '';
  $username_error = (isset($flash['errors']['username'])) ? $flash['errors']['username'] : '';
  $password_error = (isset($flash['errors']['password'])) ? $flash['errors']['password'] : '';

  $app->render('login.php', array(
      'error'          => $error,
      'username_value' => $username_value,
      'username_error' => $username_error,
      'password_error' => $password_error,
      'urlRedirect'    => $urlRedirect
     ));
})->name('tdw_get_login'); // GET /login

// POST /login https://github.com/briannesbitt/Slim-ContextSensitiveLoginLogout
$app->post(SLIM_APP_ROUTE . '/login', function () use ($app) {
  $app->log->addDebug($app->request->getResourceUri(), [$app->request->getMethod()]);
  $username = $app->request->post('username');
  $password = $app->request->post('password');

  $errors = array();
  $em = GetEntityManager();
  $userRepository = $em->getRepository('TDW\UserApi\Entity\User');
  $user = $userRepository->findOneByUsername(mb_convert_encoding($username, 'ISO-8859-1', 'UTF-8'));
  if (empty($user)) {
    $errors['username'] = 'Username is not found.';
  }
  else if (!$user->validatePassword($password)) {
    $app->flash('username', $username);
    $errors['password'] = 'Password does not match.';
  }
  if (count($errors) > 0) { // errors?
    $app->flash('errors', $errors);
    $app->log->addInfo("Login error: $username");
    $app->redirect(SLIM_APP_ROUTE . '/login');
  }
  $_SESSION['user'] = $username;
  $_SESSION['isAdmin'] = $user->getIsAdmin();
  $app->log->addInfo('Login Ok');
  if (isset($_SESSION['urlRedirect'])) {
    $tmp = $_SESSION['urlRedirect'];
    unset($_SESSION['urlRedirect']);
    $app->redirect($tmp);
  }

  $app->redirect(filter_input(INPUT_SERVER, 'HTTP_REFERER'));
})->name('tdw_post_login'); // POST /login

$app->error(function (\Exception $e) use ($app) {
  $app->log->addError($app->request->getResourceUri(),
          ['method'  => $app->request->getMethod(),
           'message' => $e->getMessage()]);
  $app->render('error.php');
});

// DI loggger - Define log resource
$app->container->singleton('log', function () use ($app) {
  $log = new Logger(SLIM_LOG_NAME);
  $dateFormat = "Y-m-d H:i:s";
  $ip = $app->request->getIp();
  $output = "[%datetime%] [%level_name%] [$ip] \"%message%\" %context% %extra%\n";
  $formatter = new LineFormatter($output, $dateFormat);
  $streamHandler = new RotatingFileHandler(SLIM_LOG_PATH . SLIM_LOG_FILE, SLIM_LOG_MAXFILES, SLIM_LOG_LEVEL);
  $streamHandler->setFormatter($formatter);
  $log->pushProcessor(function ($record) {
    $record['extra']['user'] = (IsSet($_SESSION['user'])) ? $_SESSION['user'] : NULL;
    return $record;
  });
  $log->pushHandler($streamHandler);

  return $log;
});
