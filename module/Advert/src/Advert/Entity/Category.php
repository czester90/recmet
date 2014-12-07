<?php

namespace Advert\Entity;

use Library\HttpServiceCaller;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Category")
 *
 */
class Category {

  /**
   * @var int
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="integer",nullable=true)
   * @ORM\OneToMany(targetEntity="Category")
   */
  protected $parent_id;

  /**
   * @ORM\Column(type="string")
   */
  protected $name;

  /**
   * @ORM\Column(type="string")
   */
  protected $url;

  /**
   * @ORM\Column(type="integer")
   */
  protected $position;

    public function buildForm($name, $parent, $position) {
        $this->setName($name);
        $this->setParent_id($parent);
        $this->setPosition($position);
        $this->setUrl(HttpServiceCaller::toAscii($name));
    }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = (int) $id;
  }
  
  public function getParentId() {
    return $this->parent_id;
  }

  public function getName() {
    return $this->name;
  }

  public function getUrl() {
    return $this->url;
  }

  public function getPosition() {
    return $this->position;
  }

  public function setParentId($parent_id) {
    $this->parent_id = $parent_id;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  public function setPosition($position) {
    $this->position = $position;
  }

}
