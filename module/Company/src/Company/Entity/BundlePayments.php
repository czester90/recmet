<?php

namespace Company\Entity;

use Application\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Bundle_Payments")
 *
 */
class BundlePayments extends BaseEntity {

    /**
     * Initialies the roles variable.
     */
    public function __construct() {
        $this->updated_at = new \DateTime();
        $this->created_at  = new \DateTime();
        $this->finished_at = new \DateTime();
        $this->finished_at->add(new \DateInterval('P1M'));
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
     * @ORM\ManyToOne(targetEntity="Company")
     */
    protected $company_id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="BundleDetails")
     */
    protected $bundle_details_id;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $paid;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $finished_at;

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
     * @param mixed $finished_at
     */
    public function setFinishedAt($finished_at)
    {
        $this->finished_at = $finished_at;
    }

    /**
     * @return mixed
     */
    public function getFinishedAt()
    {
        return $this->finished_at;
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
     * @param boolean $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param int $bundle_details_id
     */
    public function setPakiet($bundle_details_id)
    {
        $this->bundle_details_id = $bundle_details_id;
    }

    /**
     * @return int
     */
    public function getPakiet()
    {
        return $this->bundle_details_id;
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