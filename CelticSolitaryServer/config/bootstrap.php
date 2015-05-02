<?php   // config/bootstrap.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;

function GetEntityManager() {
  
  $namespaces = array(
    __DIR__ . '/../config/yaml' => 'TDW\UserApi\Entity'
  );

  // $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
  // $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
  // $config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
  $config = new Configuration;
  $driverImpl = new SimplifiedYamlDriver($namespaces);
  $config->setMetadataDriverImpl($driverImpl);
  $config->setProxyDir('\xampp\tmp');
  $config->setProxyNamespace('TDW\UserApi\Proxies');

  // the connection configuration
  $dbParams = array(
      'driver'   => MYSQL_DRIVER,
      'user'     => MYSQL_USER,
      'password' => MYSQL_PASSWORD,
      'dbname'   => MYSQL_SCHEMA_NAME
  );

  return EntityManager::create($dbParams, $config);
}