<?php

namespace Advert\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseEntity;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Adverts")
 *
 */
class Advert extends BaseEntity {

  const AMOUNT_TYPE_ONE = 'Jednorazowo';
  const AMOUNT_TYPE_MONTH = 'Miesięcznie';
  
  const ADVERT_TYPE_SELL = 'Sprzedaje';
  const ADVERT_TYPE_BUY = 'Poszukuje';

  private static $advert_type_arr = array(
    1 => Advert::ADVERT_TYPE_SELL,
    2 => Advert::ADVERT_TYPE_BUY
  );
  
  private static $amount_type_arr = array(
    1 => Advert::AMOUNT_TYPE_ONE,
    2 => Advert::AMOUNT_TYPE_MONTH
  );
  
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
   * 
  * @ORM\ManyToMany(targetEntity="Image", mappedBy="advert_id")
  */
  protected $images; 

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
  protected $advert_type;
  
  /**
   * @ORM\Column(type="integer")
   */
  protected $amount_type;
  
  /**
   * @ORM\Column(type="integer")
   */
  protected $days;
  
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
    $this->updated_at = new \DateTime();
    $this->created_at  = new \DateTime();
    $this->images = new ArrayCollection();
  }

  public static function amountTypeArray($type){
    return self::$amount_type_arr[$type];
  }
  
  public static function advertTypeArray($type){
    return self::$advert_type_arr[$type];
  }

  public function getImagesValue() {
    return $this->images->getValues();
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

  public function getAmountType() {
    return $this->amount_type;
  }

  public function setAmountType($amount_type) {
    $this->amount_type = $amount_type;
  }

  public function getAdvertType() {
    return $this->advert_type;
  }

  public function setAdvertType($advert_type) {
    $this->advert_type = $advert_type;
  }


}
