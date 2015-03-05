<?php

namespace Advert\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="Advert\Entity\Repository\OfferRepository")
 * @ORM\Table(name="Offers")
 *
 */
class Offer
{

    const TYPE_ACCEPT = 1;
    const TYPE_REJECTED = 2;
    const TYPE_SENT = 3;
    const TYPE_BUY = 4;

    private static $typeNames = array(
        1 => 'Zaakceptowana',
        2 => 'Odrzucona',
        3 => 'Wysłana',
        4 => 'Zakończona'
    );

    public static function getNameOfType($type)
    {
        return self::$typeNames[$type];
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
     * @ORM\ManyToOne(targetEntity="Company\Entity\Company")
     */
    protected $company_id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Company\Entity\Company")
     */
    protected $send_company_id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Advert\Entity\Advert")
     */
    protected $advert_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $amount;

    /**
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $seen = 0;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    public function __construct()
    {
        $this->updated_at = new \DateTime();
        $this->created_at = new \DateTime();
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
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
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

    /**
     * @param mixed $seen
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    }

    /**
     * @return mixed
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * @param mixed $send_company_id
     */
    public function setSendCompanyId($send_company_id)
    {
        $this->send_company_id = $send_company_id;
    }

    /**
     * @return mixed
     */
    public function getSendCompanyId()
    {
        return $this->send_company_id;
    }



}
