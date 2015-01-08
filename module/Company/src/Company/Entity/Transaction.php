<?php

namespace Company\Entity;

use Application\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Transaction")
 *
 */
class Transaction extends BaseEntity {

    /**
     * Initialies the roles variable.
     */
    public function __construct() {
        $this->updated_at = new \DateTime();
        $this->created_at  = new \DateTime();
    }

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Advert\Entity\Advert")
     */
    protected $advert_id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Company")
     */
    protected $company_id;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $accept;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $done;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    public static function Amount($amount)
    {
        return number_format((float)$amount, 2, '.', '');
    }

    /**
     * @param int $accept
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    /**
     * @return int
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @param mixed $advert_id
     */
    public function setAdvertId($advert_id)
    {
        $this->advert_id = $advert_id;
    }

    /**
     * @return mixed
     */
    public function getAdvertId()
    {
        return $this->advert_id;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $company_id
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param boolean $done
     */
    public function setDone($done)
    {
        $this->done = $done;
    }

    /**
     * @return boolean
     */
    public function getDone()
    {
        return $this->done;
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
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}