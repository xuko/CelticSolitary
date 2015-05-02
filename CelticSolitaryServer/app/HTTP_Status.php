<?php // src/HTTP_Status.php

namespace TDW\UserApi\Controller;

/**
 * HTTP_Status
 * A simple class to handle the HTTP status
 *
 */
class HTTP_Status implements \JsonSerializable {

  /** @var integer  */
  protected $code;
  
  /** @var string  */
  protected $message;
  
  public function __construct($code, $message) {
    $this->code = $code;
    $this->message = $message;
  }

  /**
   * Get HTTP_Status code
   * @return integer
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * Get HTTP_Status message
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * Set HTTP_Status code
   * @param integer $code
   * @return \TDW\UserApi\Controller\HTTP_Status
   */
  public function setCode($code) {
    $this->code = $code;
    return $this;
  }

  /**
   * Set HTTP_Status message
   * @param string $message
   * @return \TDW\UserApi\Controller\HTTP_Status
   */
  public function setMessage($message) {
    $this->message = $message;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function jsonSerialize() {
    return array(
      'code' => $this->code,
      'message' => $this->message
      );
  }
}
