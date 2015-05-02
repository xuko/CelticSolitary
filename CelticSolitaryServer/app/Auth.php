<?php // app/Auth.php

namespace TDW\UserApi\Controller;

/**
 * Class Auth
 * This is never going to authenticate anyone.
 * However, when we mock this we'll make it pass.
 */
class Auth {

  public function authenticate() {
    return isset($_SESSION['isAdmin'])
            ? $_SESSION['isAdmin']
            : FALSE;
  }
}
