<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 16/10/14
 * Time: 20:08
 */

namespace Company\Entity;

use Application\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="City")
 *
 */
class City extends BaseEntity {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Province")
     */
    protected $province_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;
} 