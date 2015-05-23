<?php

namespace Company\Entity;

use Application\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Company")
 *
 */
class Company extends BaseEntity {


    /**
     * Initialies the roles variable.
     */
    public function __construct() {
        $this->updated_at = new \DateTime();
        $this->created_at  = new \DateTime();
    }

  /**
   * @var int
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $name;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $nip;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $regon;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $country;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $address;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $city;
  
  /**
   * @var string
   * @ORM\Column(type="string")
   */
  protected $post_code;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="BundleDetails")
     */
    protected $bundle_details_id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $rank;

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
    $this->id = (int) $id;
  }

    public function getNip() {
        return $this->nip;
    }

    public function setNip($value) {
        $this->nip = $value;
    }

    public function getRegon() {
        return $this->regon;
    }

    public function setRegon($value) {
        $this->regon = $value;
    }

  public function getName() {
    return $this->name;
  }

  public function setName($value) {
    $this->name = $value;
  }
  
  public function getCountry() {
    return $this->country;
  }
  
  public function setCountry($value){
    $this->country = $value;
  }

  public function getAddress() {
    return $this->address;
  }
  
  public function setAddress($value){
    $this->address = $value;
  }

  public function getCity() {
    return $this->city;
  }
  
  public function setCity($value){
    $this->city = $value;
  }

  public function getPostCode() {
    return $this->post_code;
  }
  
  public function setPostCode($value){
    $this->post_code = $value;
  }

    /**
     * @param int $pakiet
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
     * @param int $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
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

    public static function rank($rank, $style = 'float: none;')
    {
        $arr = array();
        $index = 0;
        $html = "";

        for($i = 0; $i < 5; $i++){
            $arr[$i] = $rank > 0 ? 'blue' : '';
            $index = $index + 2;
        }

        $html .= "<div class='star-rating' style='".$style."'>";
        foreach($arr as $key => $star){
            $html .= "<button class='star ".$star."'>".$key." Star</button>";
        }
        $html .= "</div>";

        return $html;
    }

    /**
     * @param mixed $bundle_details_id
     */
    public function setBundleDetailsId($bundle_details_id)
    {
        $this->bundle_details_id = $bundle_details_id;
    }

    /**
     * @return mixed
     */
    public function getBundleDetailsId()
    {
        return $this->bundle_details_id;
    }


}
