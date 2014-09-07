<?php

namespace Company\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Adverts")
 *
 */
class Advert {

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
   * @ORM\ManyToOne(targetEntity="Category")
   */
  protected $category_id;

  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $name;
  
  /**
   * @var string
   * @ORM\Column(type="text")
   */
  protected $description;
  
  /**
   * @ORM\Column(type="string")
   */
  protected $url;

  /**
   * @ORM\Column(type="boolean")
   */
  protected $active = 1;
  
  /**
   * @ORM\Column(type="float")
   */
  protected $amount;
  
  /**
   * @ORM\Column(type="integer")
   */
  protected $days;
  
  /**
   * @ORM\ManyToOne(targetEntity="Image")
   */
  protected $images;
  
  /**
   * @ORM\Column(type="float")
   */
  protected $pieces;
  
  /**
   * @ORM\Column(type="integer")
   */
  protected $visits = 0;
  
  /**
   * @ORM\Column(type="datetime")
   */
  protected $updated_at;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $created_at;
  
  public function __construct() {
    $this->modified_at = new \DateTime();
    $this->created_at  = new \DateTime();
  }


  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = (int) $id;
  }
  public function getUser_id() {
    return $this->user_id;
  }

  public function getCategory_id() {
    return $this->category_id;
  }

  public function getName() {
    return $this->name;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getUrl() {
    return $this->url;
  }

  public function getActive() {
    return $this->active;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function getDays() {
    return $this->days;
  }

  public function getImages() {
    return $this->images;
  }

  public function getPieces() {
    return $this->pieces;
  }

  public function getVisits() {
    return $this->visits;
  }

  public function getUpdated_at() {
    return $this->updated_at;
  }

  public function getCreated_at() {
    return $this->created_at;
  }

  public function setUser_id($user_id) {
    $this->user_id = $user_id;
  }

  public function setCategory_id($category_id) {
    $this->category_id = $category_id;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  public function setActive($active) {
    $this->active = $active;
  }

  public function setAmount($amount) {
    $this->amount = $amount;
  }

  public function setDays($days) {
    $this->days = $days;
  }

  public function setImages($images) {
    $this->images = $images;
  }

  public function setPieces($pieces) {
    $this->pieces = $pieces;
  }

  public function setVisits($visits) {
    $this->visits = $visits;
  }

  public function setUpdated_at($updated_at) {
    $this->updated_at = $updated_at;
  }

  public function setCreated_at($created_at) {
    $this->created_at = $created_at;
  }


}
