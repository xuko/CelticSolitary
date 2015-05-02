<?php // src/MySlim.php

namespace TDW\UserApi\Controller;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * MySlim Class
 * http://gotoanswer.stanford.edu/?q=Rest+API+and+Slim+Framework
 * 
 */
class MySlim extends \Slim\Slim {

  function outputData($data) {
    $this->response->headers->set('Content-Type', 'application/json');
    if ($data instanceof HTTP_Status) {
      $this->response->setStatus($data->getCode());
      $this->response->setBody(json_encode($data, JSON_PRETTY_PRINT));
    } else {
      switch($this->request->headers->get('Accept')) {
        case 'application/json':
        default:
          $this->response->headers->set('Content-Type', 'application/json');
          echo json_encode($data);        
      }
    }

    return $this;
  }
}