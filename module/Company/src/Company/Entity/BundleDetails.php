<?php

namespace Company\Entity;

use Application\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Bundle_Details")
 *
 */
class BundleDetails extends BaseEntity {

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
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $amount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $option_advert;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $option_advert_day;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $option_message;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $option_site;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $option_store;

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $option_advert
     */
    public function setOptionAdvert($option_advert)
    {
        $this->option_advert = $option_advert;
    }

    /**
     * @return int
     */
    public function getOptionAdvert()
    {
        return $this->option_advert;
    }

    /**
     * @param boolean $option_message
     */
    public function setOptionMessage($option_message)
    {
        $this->option_message = $option_message;
    }

    /**
     * @return boolean
     */
    public function getOptionMessage()
    {
        return $this->option_message;
    }

    /**
     * @param boolean $option_site
     */
    public function setOptionSite($option_site)
    {
        $this->option_site = $option_site;
    }

    /**
     * @return boolean
     */
    public function getOptionSite()
    {
        return $this->option_site;
    }

    /**
     * @param boolean $option_store
     */
    public function setOptionStore($option_store)
    {
        $this->option_store = $option_store;
    }

    /**
     * @return boolean
     */
    public function getOptionStore()
    {
        return $this->option_store;
    }

    /**
     * @param int $option_advert_day
     */
    public function setOptionAdvertDay($option_advert_day)
    {
        $this->option_advert_day = $option_advert_day;
    }

    /**
     * @return int
     */
    public function getOptionAdvertDay()
    {
        return $this->option_advert_day;
    }


}