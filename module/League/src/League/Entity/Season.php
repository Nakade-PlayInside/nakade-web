<?php
namespace League\Entity;

use League\Entity\Rules;
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
 * @property boolean $_closed
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
   * active
   *
   * @ORM\Column(name="active", type="boolean")
   * @var boolean
   * @access protected
   */
  protected $_active;
  
  /**
   * closed
   *
   * @ORM\Column(name="closed", type="boolean")
   * @var boolean
   * @access protected
   */
  protected $_closed;
  
  /**
   * Year
   *
   * @ORM\Column(name="year", type="date")
   * @var DateTime
   * @access protected
   */
  protected $_year;

  /**
   * rulesId
   *
   * @ORM\Column(name="rulesId", type="integer")
   * @var int
   * @access protected
   */
   protected $_rulesId;
   
  /**
   * rulesId: Foreign Key
   * Rules Entity
   *
   * @ORM\OneToOne(targetEntity="League\Entity\Rules")
   * @ORM\JoinColumn(name="rulesId", referencedColumnName="id")
   * @var Rules
   * @access protected
   */
   protected $_rules;
   
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
   * Returns the Active flag.
   * True if all conditions are set to let the season
   * begin ie leagues and players are bound.
   *
   * @access public
   * @return boolean
   */
  public function isActive()
  {
    return $this->_active;
  }
  
  /**
   * Sets the Closed flag 
   *
   * @param boolean $closed
   * @access public
   * @return Season
   */
  public function setClosed($closed)
  {
    $this->_closed = $closed;
    return $this;
  }

  /**
   * Returns the Closed flag.
   * True if all games in this season 
   * have been played. 
   *
   * @access public
   * @return boolean
   */
  public function isClosed()
  {
    return $this->_closed;
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
  
  /**
   * Sets the rulesId
   * @param int $rulesId
   * @access public
   * @return Season
   */
  public function setRulesId($rid)
  {
    $this->_rulesId = $rid;
    return $this;
  }

  /**
   * Returns the rulesId
   *
   * @access public
   * @return int
   */
  public function getRulesId()
  {
    
      return $this->_rulesId;
  }
  
  /**
   * Sets the Rules entity
   * @param int $rulesId
   * @access public
   * @return Season
   */
  public function setRules($rules)
  {
    $this->_rules = $rules;
    return $this;
  }

  /**
   * Returns the Rules entity
   *
   * @access public
   * @return Rules
   */
  public function getRules()
  {
    
      return $this->_rules;
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