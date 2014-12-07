<?php

namespace User\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */

class User implements UserInterface, ProviderInterface {

  /**
   * Initialies the roles variable.
   */
  public function __construct() {
    $this->company_id = new ArrayCollection();
    $this->roles = new ArrayCollection();
    $this->updated_at = new \DateTime();
    $this->created_at  = new \DateTime();
  }
  
  /**
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  protected $id;
  
  /**
   * @ORM\Column(type="integer")
   * @ORM\ManyToOne(targetEntity="Company/Entity/Company")
   */
  protected $company_id;

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
   * @ORM\JoinTable(name="Users_roles",
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
  
  /**
   * @ORM\Column(type="datetime")
   */
  protected $updated_at;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $created_at;

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    return $this->id = $id;
  }
  
  public function getCompany_id() {
    return $this->company_id;
  }

  public function setCompany_id($company_id) {
    $this->company_id = $company_id;
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

  public function getDisplayName() {
    
  }

  public function setDisplayName($displayName) {
    
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
     * @param string $password_recovery_hash
     */
    public function setPasswordRecoveryHash($password_recovery_hash)
    {
        $this->password_recovery_hash = $password_recovery_hash;
    }

    /**
     * @return string
     */
    public function getPasswordRecoveryHash()
    {
        return $this->password_recovery_hash;
    }

}
