<?php // app/Entity/Group.php

namespace TDW\UserApi\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\ORM\EntityRepository;

/**
 * Group
 */
class Group implements \Serializable, \JsonSerializable {
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $groupname;

    /**
     * @var string
     */
    private $description;

    public function __construct($groupname, $description = NULL) {
      $this->groupname   = $groupname;
      $this->description = $description;
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
     * Set groupname
     *
     * @param string $group
     * @return Group
     */
    public function setGroupname($group) {
      $this->groupname = $group;

      return $this;
    }

    /**
     * Get groupname
     *
     * @return string 
     */
    public function getGroupname() {
      return $this->groupname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Group
     */
    public function setDescription($description) {
      $this->description = $description;

      return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
      return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() {
      return $this->groupname;
    }
    
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() {
      return array(
        'id'          => $this->id,
        'groupname'   => utf8_encode($this->groupname),
        'description' => utf8_encode($this->description)
      );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() {
      return serialize(array(
        $this->id,
        $this->groupname,
        $this->description
      ));      
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized) {
      list(
        $this->id,
        $this->groupname,
        $this->description
      ) = unserialize($serialized);
    }
}

/**
 * GroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
//class GroupRepository extends EntityRepository {
//  
//}
