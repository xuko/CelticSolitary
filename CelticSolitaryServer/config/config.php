<?php   // src/config.php

/**
 * MySQL Config
 */
define('MYSQL_DRIVER', 'pdo_mysql');
define('MYSQL_USER', 'tdw_user');
define('MYSQL_PASSWORD', '*tdw_user*');
define('MYSQL_SCHEMA_NAME', 'tdw_user');

/**
 * Server config
 */
define('SLIM_SERVER_NAME', 'localhost');
/** Server port */
define('SLIM_SERVER_PORT', 8000);

/**
 * Every Slim application may be given a name. This is optional.
 */
define('SLIM_APP_NAME', 'TDW_UserApi');

define('SLIM_APP_ROUTE', '/TDW_UserApi/v1');
define('SLIM_API_ROUTE', '/api/index.html');

/**
 * Slim Config
 */
define('SLIM_DEBUG', TRUE);

/**
 *  “development”, “test”, “production”
 */
define('SLIM_MODE', 'development');
define('SLIM_TEMPLATES_PATH', __DIR__ . '/../app/resources/templates');

/**
 * Slim Log
 */
/**
 * Logging levels from syslog protocol defined in RFC 5424:
 * DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY */
define('SLIM_LOG_LEVEL', \Slim\Log::DEBUG);
define('SLIM_LOG_ENABLED', TRUE);
define('SLIM_LOG_NAME', 'TDW_UserApi_Log');
define('SLIM_LOG_PATH', __DIR__ . '/../build/logs/');
define('SLIM_LOG_FILE', 'TDW_Api' . '.log');
/** The maximal amount of files to keep (0 means unlimited) */
define('SLIM_LOG_MAXFILES', 7);
define('SLIM_LOG_ERRORS', SLIM_LOG_PATH . 'errors.log');

/**
 * Slim Cookies
 */
define('SLIM_COOKIES_ENCRYPT', TRUE);
define('SLIM_COOKIES_LIFETIME', '5 minutes');
define('SLIM_COOKIES_PATH', '/');
define('SLIM_COOKIES_DOMAIN', NULL); // Ojo: Problemas con Chrome si 'localhost'
define('SLIM_COOKIES_SECURE', FALSE);

/**
 * Determines whether cookies should be accessible through client 
 * side scripts (false = accessible)
 */
define('SLIM_COOKIES_HTTP_ONLY', FALSE);

/**
 * The secret key used for cookie encryption. You should change this setting
 * if you use encrypted HTTP cookies in your Slim application.
 */
define('SLIM_COOKIES_SECRET_KEY', 'SlimTDWUserApiSecretWord');

/**
 * The mcrypt cipher used for HTTP cookie encryption.
 * See available ciphers (http://php.net/manual/en/mcrypt.ciphers.php)
 */
define('SLIM_COOKIES_CIPHER', MCRYPT_RIJNDAEL_256);

/**
 * The mcrypt cipher mode used for HTTP cookie encryption.
 * See available cipher modes (http://www.php.net/manual/en/mcrypt.constants.php)
 */
define('SLIM_COOKIES_CIPHER_MODE', MCRYPT_MODE_CBC);

/**
 * By default, Slim returns an HTTP/1.1 response to the client.
 * Use this setting if you need to return an HTTP/1.0 response. 
 */
define('SLIM_HTTP_VERSION', '1.1');

/**
 * Session name
 */
define('SLIM_SESION_NAME', 'TDW_Slim_Session');
