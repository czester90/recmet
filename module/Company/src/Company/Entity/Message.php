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
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

} 