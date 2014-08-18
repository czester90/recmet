<?php

namespace User\Entity;

use Application\Entity\BaseEntity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class User extends BaseEntity implements UserInterface, ProviderInterface {

  /**
   * Initialies the roles variable.
   */
  public function __construct() {
    parent::__construct();
    $this->roles = new ArrayCollection();
  }

  /**
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  protected $username;

  /**
   * @var string
   * @ORM\Column(type="string", unique=true,  length=255)
   */
  protected $email;

  /**
   * @var string
   * @ORM\Column(type="string", length=128)
   */
  protected $password;

  /**
   * @var \Doctrine\Common\Collections\Collection
   * @ORM\ManyToMany(targetEntity="User\Entity\Role", cascade={"persist"})
   * @ORM\JoinTable(name="users_roles",
   *    joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
   *    inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
   * )
   */
  protected $roles;

  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $first_name;

  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $last_name;

  /**
   * @var string
   * @ORM\Column(type="datetime", nullable=true)
   */
  protected $last_login;

  /**
   * @var string
   * @ORM\Column(type="string", length=41, nullable=true)
   */
  protected $password_recovery_hash;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    return $this->id = $id;
  }

  public function getUsername() {
    return $this->username;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  public function getRoles() {
    return $this->roles->getValues();
  }

  public function getRolesAdd() {
    return $this->roles;
  }

  public function addRole($role) {
    $this->roles[] = $role;
  }

  public function getFirstName() {
    return $this->first_name;
  }

  public function setFirstName($first_name) {
    $this->first_name = $first_name;
  }

  public function getLastName() {
    return $this->last_name;
  }

  public function setLastName($last_name) {
    $this->last_name = $last_name;
  }

  public function getLastLogin() {
    return $this->last_login;
  }

  public function setLastLogin($last_login) {
    $this->last_login = $last_login;
  }
}
