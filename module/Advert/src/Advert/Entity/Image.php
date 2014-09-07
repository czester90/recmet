<?php

namespace Company\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Images")
 *
 */
class Image {

  /**
   * @var int
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="integer")
   * @ORM\ManyToOne(targetEntity="Users")
   */
  protected $user_id;

  /**
   * @ORM\Column(type="integer")
   * @ORM\ManyToOne(targetEntity="Advert")
   */
  protected $advert_id;

  /**
   * @ORM\Column(type="string")
   */
  protected $name;

  /**
   * @ORM\Column(type="string")
   */
  protected $type;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = (int) $id;
  }

  public function getUser_id() {
    return $this->user_id;
  }

  public function getAdvert_id() {
    return $this->advert_id;
  }

  public function getName() {
    return $this->name;
  }

  public function getType() {
    return $this->type;
  }

  public function setUser_id($user_id) {
    $this->user_id = $user_id;
  }

  public function setAdvert_id($advert_id) {
    $this->advert_id = $advert_id;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setType($type) {
    $this->type = $type;
  }

}
