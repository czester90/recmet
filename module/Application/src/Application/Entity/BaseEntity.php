<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @ORM\MappedSuperclass
 */
class BaseEntity
{

    const DATE_FORMAT = 'd-m-Y H:i:s';

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        return $this->$name = $value;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function setData($data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}