<?php

namespace Advert\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Offers")
 *
 */
class Offer {

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
  protected $description;


  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = (int) $id;
  }

}
