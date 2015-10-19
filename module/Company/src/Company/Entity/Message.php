<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 11/02/15
 * Time: 17:34
 */

namespace Company\Entity;

use Application\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Message")
 *
 */
class Message extends BaseEntity {

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
    protected $receiver_company;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Company\Entity\Company")
     */
    protected $sender_company;

    /**
     * @ORM\Column(type="integer")
     */
    protected $system_message = false;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var text
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $seen = 0;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $is_delete = false;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * Initialies the roles variable.
     */
    public function __construct() {
        $this->updated_at = new \DateTime();
        $this->created_at  = new \DateTime();
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
     * @param \Company\Entity\text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \Company\Entity\text
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param mixed $receiver_company
     */
    public function setReceiverCompany($receiver_company)
    {
        $this->receiver_company = $receiver_company;
    }

    /**
     * @return mixed
     */
    public function getReceiverCompany()
    {
        return $this->receiver_company;
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
     * @param mixed $sender_company
     */
    public function setSenderCompany($sender_company)
    {
        $this->sender_company = $sender_company;
    }

    /**
     * @return mixed
     */
    public function getSenderCompany()
    {
        return $this->sender_company;
    }

    /**
     * @param mixed $system_message
     */
    public function setSystemMessage($system_message)
    {
        $this->system_message = $system_message;
    }

    /**
     * @return mixed
     */
    public function getSystemMessage()
    {
        return $this->system_message;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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