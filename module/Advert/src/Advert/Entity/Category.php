<?php

namespace Advert\Entity;

use Library\HttpServiceCaller;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="Advert\Entity\Repository\CategoryRepository")
 * @ORM\Table(name="Category")
 *
 */
class Category
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @ORM\OneToMany(targetEntity="Category")
     */
    protected $original_id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @ORM\OneToMany(targetEntity="Category")
     */
    protected $parent_id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $have_child;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @ORM\Column(type="integer")
     */
    protected $position;

    public function buildForm($name, $original, $parent, $have_child, $position)
    {
        $this->setName($name);
        $this->setOriginalId($original);
        $this->setParentId($parent);
        $this->setHaveChild($have_child);
        $this->setPosition($position);
        $this->setUrl(HttpServiceCaller::toAscii($name));
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @param mixed $have_child
     */
    public function setHaveChild($have_child)
    {
        $this->have_child = $have_child;
    }

    /**
     * @return mixed
     */
    public function getHaveChild()
    {
        return $this->have_child;
    }

    /**
     * @param mixed $original_id
     */
    public function setOriginalId($original_id)
    {
        $this->original_id = $original_id;
    }

    /**
     * @return mixed
     */
    public function getOriginalId()
    {
        return $this->original_id;
    }


}
