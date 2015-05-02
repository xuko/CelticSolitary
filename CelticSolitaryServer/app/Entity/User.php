<?php // app/Entity/User.php

namespace TDW\UserApi\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\ORM\EntityRepository;

/**
 * User
 */
class User implements \Serializable, \JsonSerializable {

  /**
   * @var integer
   */
  private $id;

  /**
   * @var string
   */
  private $username;

  /**
   * @var string
   */
  private $email;

  /**
   * @var string
   */
  private $password;

  /**
   * @var \DateTime
   */
  private $createTime;

  /**
   * @var boolean
   */
  private $isAdmin;

  /**
   * @var boolean
   */
  private $isActive;

  /**
   * @var string
   */
  private $note;

  /**
   * @var Group
   */
  private $group;

  /**
   * Constructor
   */
  public function __construct() {
    $this->createTime = new \DateTime();
    $this->isActive   = TRUE;
    $this->isAdmin    = FALSE;
  }

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set username
   *
   * @param string $username
   * @return User
   */
  public function setUsername($username) {
    $this->username = $username;

    return $this;
  }

  /**
   * Get username
   *
   * @return string 
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Set email
   *
   * @param string $email
   * @return User
   */
  public function setEmail($email) {
    $this->email = $email;

    return $this;
  }

  /**
   * Get email
   *
   * @return string 
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set password
   *
   * @param string $password
   * @return User
   */
  public function setPassword($password) {
    $this->password = password_hash($password, PASSWORD_DEFAULT);

    return $this;
  }

  /**
   * Get password hash
   *
   * @return string 
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * Verifies that the given hash matches the user password.
   * 
   * @param string $password
   * @return boolean
   */
  public function validatePassword($password) {
    return password_verify($password, $this->password);
  }

  /**
   * Set createTime
   *
   * @param \DateTime $createTime
   * @return User
   */
  public function setCreateTime($createTime) {
    $this->createTime = $createTime;

    return $this;
  }

  /**
   * Get createTime
   *
   * @return \DateTime 
   */
  public function getCreateTime() {
    return $this->createTime;
  }

  /**
   * Set isAdmin
   *
   * @param boolean $isAdmin
   * @return User
   */
  public function setIsAdmin($isAdmin) {
    $this->isAdmin = $isAdmin;

    return $this;
  }

  /**
   * Get isAdmin
   *
   * @return boolean 
   */
  public function getIsAdmin() {
    return $this->isAdmin;
  }

  /**
   * Set isActive
   *
   * @param boolean $isActive
   * @return User
   */
  public function setIsActive($isActive) {
    $this->isActive = $isActive;

    return $this;
  }

  /**
   * Get isActive
   *
   * @return boolean 
   */
  public function getIsActive() {
    return $this->isActive;
  }

  /**
   * Set note
   *
   * @param string $note
   * @return User
   */
  public function setNote($note) {
    $this->note = $note;

    return $this;
  }

  /**
   * Get note
   *
   * @return string 
   */
  public function getNote() {
    return $this->note;
  }

  /**
   * Set group
   *
   * @param \AppBundle\Entity\Group $group
   * @return User
   */
  public function setGroup(Group $group = NULL) {
    $this->group = $group;

    return $this;
  }

  /**
   * Get group
   *
   * @return Group 
   */
  public function getGroup() {
    return $this->group;
  }

  /**
   * @see \Serializable::serialize()
   */
  public function serialize() {
    return serialize(array(
      $this->id,
      $this->username,
      // $this->password,
      $this->isActive,
      $this->isAdmin,
      $this->email
    ));
  }

  /**
   * @see \Serializable::unserialize()
   */
  public function unserialize($serialized) {
    list(
      $this->id,
      $this->username,
      // $this->password,
      $this->isActive,
      $this->isAdmin,
      $this->email
      ) = unserialize($serialized);
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return $this->username;
  }

  /**
   * {@inheritdoc}
   */
  public function jsonSerialize() {
    return array(
      'id'         => $this->id, 
      'username'   => utf8_encode($this->username),
      'email'      => utf8_encode($this->email),
      // 'password'   => utf8_encode($this->password),
      'createTime' => $this->createTime,
      'isActive'   => $this->isActive,
      'isAdmin'    => $this->isAdmin,
      'group_id'   => $this->group,
      'note'       => utf8_encode($this->note)
      );
  }

}

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
//class UserRepository extends EntityRepository {
//
//  public function getAllAdminUsers() {
//    return $this->_em->createQuery('SELECT u FROM TDW\UserApi\Entity\User u WHERE u.isAdmin = "1"')
//                    ->getResult();
//  }
//
//}
