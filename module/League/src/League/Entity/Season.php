<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a User
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueSeason")
 * @property int $_id
 * @property int $_number
 * @property string $_title
 * @property string $_abbreviation
 * @property DateTime $_year
 * @property boolean $_active
 */
class Season
{
  
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="sid", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var integer
   * @access protected
   */
  protected $_id;

  /**
   * Title
   *
   * @ORM\Column(name="title", type="string")
   * @var string
   * @access protected
   */
  protected $_title;

  /**
   * Number
   *
   * @ORM\Column(name="number", type="integer")
   * @var int
   * @access protected
   */
  protected $_number;
  
  /**
   * Abbreviation
   *
   * @ORM\Column(name="abbr", type="string")
   * @var string
   * @access protected
   */
  protected $_abbreviation;
  
  
  /**
   * active
   *
   * @ORM\Column(name="active", type="boolean")
   * @var boolean
   * @access protected
   */
  protected $_active;
  
  /**
   * Year
   *
   * @ORM\Column(name="year", type="date")
   * @var DateTime
   * @access protected
   */
  protected $_year;

  /**
   * Sets the Identifier
   *
   * @param int $uid
   * @access public
   * @return Season
   */
  public function setId($uid)
  {
    $this->_id = $uid;
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
   * Sets the Title
   *
   * @param string $title
   * @access public
   * @return Season
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
   * Sets the Abbreviation
   *
   * @param string $abbreviation
   * @access public
   * @return Season
   */
  public function setAbbreviation($abbreviation)
  {
    $this->_abbreviation = $abbreviation;
    return $this;
  }

  /**
   * Returns the Abbreviation
   *
   * @access public
   * @return string
   */
  public function getAbbreviation()
  {
    return $this->_abbreviation;
  }
  
  /**
   * Sets the Active flag
   *
   * @param boolean $active
   * @access public
   * @return Season
   */
  public function setActive($active)
  {
    $this->_active = $active;
    return $this;
  }

  /**
   * Returns the Active flag
   *
   * @access public
   * @return boolean
   */
  public function isActive()
  {
    return $this->_active;
  }
  
  
  /**
   * Sets the Year  
   * @param DateTime $year
   * @access public
   * @return Season
   */
  public function setYear($year)
  {
    $this->_year = $year;
    return $this;
  }

  /**
   * Returns the Year
   *
   * @access public
   * @return DateTime
   */
  public function getYear()
  {
    
      return $this->_year;
  }
  
  
   /**
   * Sets the Number  
   * @param int $number
   * @access public
   * @return Season
   */
  public function setNumber($number)
  {
    $this->_number = $number;
    return $this;
  }

  /**
   * Returns the Number
   *
   * @access public
   * @return int
   */
  public function getNumber()
  {
    
      return $this->_number;
  }
}