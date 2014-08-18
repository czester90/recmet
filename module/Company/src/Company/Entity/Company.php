<?php

namespace Company\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Company")
 *
 */
class Company {

  /**
   * @var int
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $name;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $country;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $address;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $city;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $post_code;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = (int) $id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($value) {
    $this->name = $value;
  }
  
  public function getCountry() {
    return $this->billing_info->country;
  }
  
  public function setCountry($value){
    $this->country = $value;
  }

  public function getAddress() {
    return $this->billing_info->adreess;
  }
  
  public function setAddress($value){
    $this->address = $value;
  }

  public function getCity() {
    return $this->billing_info->city;
  }
  
  public function setCity($value){
    $this->city = $value;
  }

  public function getPostCode() {
    return $this->billing_info->zip;
  }
  
  public function setPostCode($value){
    $this->post_code = $value;
  }

}
