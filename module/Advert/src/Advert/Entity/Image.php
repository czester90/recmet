<?php

namespace Advert\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Images")
 *
 */
class Image
{

    const IMAGE_PATH = '/public/images/user/';
    const IMAGE_PATH_VIEW = '/images/user/';

    const ADVERT_TYPE_PHOTO = 'photo';
    const ADVERT_TYPE_ATTACH = 'attach';

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="User\Entity\Users")
     */
    protected $user_id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Advert\Entity\Advert", inversedBy="images", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="Advert_image",
     *    joinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")},
     *    inverseJoinColumns={@ORM\JoinColumn(name="advert_id", referencedColumnName="id")}
     * )
     */
    protected $advert_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $advert_type;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

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
        $this->advert_id = new ArrayCollection();
        $this->updated_at = new \DateTime();
        $this->created_at = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @param mixed $advert_type
     */
    public function setAdvertType($advert_type)
    {
        $this->advert_type = $advert_type;
    }

    /**
     * @return mixed
     */
    public function getAdvertType()
    {
        return $this->advert_type;
    }


    public function getUser_id()
    {
        return $this->user_id;
    }

    public function getAdvert_id()
    {
        return $this->advert_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setAdvert_id($advert_id)
    {
        $this->advert_id = $advert_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }


}
