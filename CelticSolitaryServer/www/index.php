<?php // public/index.php

require_once '../vendor/autoload.php';
require_once '../config/config.php';

$composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

$app = new TDW\UserApi\Controller\MySlim(
  array(
    'version'         => $composer->version,
    'debug'           => SLIM_DEBUG,
    'mode'            => SLIM_MODE,
    'templates.path'  => SLIM_TEMPLATES_PATH,
    'log.level'       => SLIM_LOG_LEVEL,
    'log.enabled'     => SLIM_LOG_ENABLED,
    'cookies.encrypt' => SLIM_COOKIES_ENCRYPT,
    'http.version'    => SLIM_HTTP_VERSION,
    )
);

$app->setName(SLIM_APP_NAME);

// Session Cookie config 
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => SLIM_COOKIES_LIFETIME,
    'path' => SLIM_COOKIES_PATH,
    'domain' => SLIM_COOKIES_DOMAIN,
    'secure' => SLIM_COOKIES_SECURE,
    'httponly' => SLIM_COOKIES_HTTP_ONLY,
    'name' => SLIM_SESION_NAME,
    'secret' => SLIM_COOKIES_SECRET_KEY,
    'cipher' => SLIM_COOKIES_CIPHER,
    'cipher_mode' => SLIM_COOKIES_CIPHER_MODE,
)));

require_once __DIR__ . '/../app/app.php';

$app->run();
