<?php

namespace League\Entity;

use League\Entity\Season;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueLigen")
 * @property int $_id
 * @property int $_sid
 * @property int $_number
 * @property string $_title

 */
class League
{

  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var int
   * @access protected
   */
  protected $_id;


  /**
   * Season Identifier
   *
   * @ORM\Column(name="sid", type="integer")
   * @var int
   * @access protected
   */
  protected $_sid;

  /**
   * League Order
   * eg 1.Liga or 2.Liga
   *
   * @ORM\Column(name="number", type="integer")
   * @var int
   * @access protected
   */
  protected $_number;

  /**
   * Title
   *
   * @ORM\Column(name="title", type="string")
   * @var string
   * @access protected
   */
  protected $_title;


  /**
   * Sets the Identifier
   *
   * @param int $lid
   *
   * @return Season
   */
  public function setId($lid)
  {
    $this->_id = $lid;
    return $this;
  }

  /**
   * Returns the Identifier
   *
   * @access public
   * @return int
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * Sets the SeasonId
   *
   * @param int $sid
   *
   * @return League
   */
  public function setSid($sid)
  {
    $this->_sid = $sid;
    return $this;
  }

  /**
   * Returns the SeasonId
   *
   * @access public
   * @return int
   */
  public function getSid()
  {
    return $this->_sid;
  }

    /**
     * @param int $number
     *
     * @return $this
     */
  public function setNumber($number)
  {
    $this->_number = $number;
    return $this;
  }

  /**
   * Returns the Order
   *
   * @access public
   * @return int
   */
  public function getNumber()
  {
    return $this->_number;
  }

  /**
   * Sets the Title
   *
   * @param string $title
   *
   * @return League
   */
  public function setTitle($title)
  {
    $this->_title = $title;
    return $this;
  }

  /**
   * Returns the Title
   *
   * @access public
   * @return string
   */
  public function getTitle()
  {
    return $this->_title;
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