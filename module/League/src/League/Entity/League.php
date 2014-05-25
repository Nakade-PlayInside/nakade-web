<?php

namespace League\Entity;

use Season\Entity\Season;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueLigen")
 */
class League
{

  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;


  /**
   * Season Identifier
   *
   * @ORM\Column(name="sid", type="integer")
   */
    private $sid;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Season", cascade={"persist"})
     * @ORM\JoinColumn(name="season", referencedColumnName="id", nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(name="name", type="string")
     */
    private $name;


    /**
   *
   * @ORM\Column(name="number", type="integer")
   */
    private $number;

  /**
   * Title
   *
   * @ORM\Column(name="title", type="string")
   * @var string
   * @access protected
   */
    private $title;


  /**
   * @param int $lid
   *
   * @return $this
   */
  public function setId($lid)
  {
    $this->id = $lid;
    return $this;
  }

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $sid
   *
   * @return $this
   */
  public function setSid($sid)
  {
    $this->sid = $sid;
    return $this;
  }

  /**
   * @return int
   */
  public function getSid()
  {
    return $this->sid;
  }

    /**
     * @param int $number
     *
     * @return $this
     */
  public function setNumber($number)
  {
    $this->number = $number;
    return $this;
  }

  /**
   * @return int
   */
  public function getNumber()
  {
    return $this->number;
  }

  /**
   * @param string $title
   *
   * @return $this
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

  /**
   * populating data as an array.
   * key of the array is getter methods name.
   *
   * @param array $data
   */
  public function populate($data)
  {
       foreach ($data as $key => $value) {

           $key = str_replace('_', '', $key);
           $method = 'set'.ucfirst($key);
           if (method_exists($this, $method)) {
                $this->$method($value);
           }
       }

  }

  /**
   * usage for creating a NEW league. Provide all neccessary values
   * in an array.
   *
   * @param array $data
   */
  public function exchangeArray($data)
  {
        $this->populate($data);

  }

   /**
    * Convert the object to an array.
    *
    * @return array
    */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}