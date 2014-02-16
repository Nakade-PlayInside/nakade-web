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
 * @property int $_drawpoints
 * @property int $_winpoints
 * @property string $_tiebreaker1
 * @property string $_tiebreaker2
 * @property string $_tiebreaker3
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
   * Year
   *
   * @ORM\Column(name="year", type="date")
   * @var DateTime
   * @access protected
   */
  protected $_year;

   /**
   * drawPoints
   *
   * @ORM\Column(name="drawPoints", type="integer")
   * @var int
   * @access protected
   */
   protected $_drawpoints;
   
   /**
   * winPoints
   *
   * @ORM\Column(name="winPoints", type="integer")
   * @var int
   * @access protected
   */
   protected $_winpoints;
   
   /**
   * tiebreaker1
   *
   * @ORM\Column(name="tiebreaker1", type="string")
   * @var string
   * @access protected
   */
   protected $_tiebreaker1;
   
   /**
   * tiebreaker2
   *
   * @ORM\Column(name="tiebreaker2", type="string")
   * @var string
   * @access protected
   */
   protected $_tiebreaker2;
   
   /**
   * winPoints
   *
   * @ORM\Column(name="tiebreaker3", type="string")
   * @var string
   * @access protected
   */
   protected $_tiebreaker3;
   
  
   
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
   * Sets the draw points
   * @param int $points
   * @access public
   * @return Season
   */
  public function setDrawpoints($points)
  {
    $this->_drawpoints = $points;
    return $this;
  }

  /**
   * Returns draw points
   *
   * @access public
   * @return int
   */
  public function getDrawpoints()
  {
    
      return $this->_drawpoints;
  }
  
  /**
   * Sets the win points
   * @param int $points
   * @access public
   * @return Season
   */
  public function setWinpoints($points)
  {
    $this->_winpoints = $points;
    return $this;
  }

  /**
   * Returns win points
   *
   * @access public
   * @return int
   */
  public function getWinpoints()
  {
    
      return $this->_winpoints;
  }
  
  /**
   * Sets the first tiebreaker
   * @param int $tiebreaker
   * @access public
   * @return Season
   */
  public function setTiebreaker1($tiebreaker)
  {
    $this->_tiebreaker1 = $tiebreaker;
    return $this;
  }

  /**
   * Returns first tiebreaker
   *
   * @access public
   * @return int
   */
  public function getTiebreaker1()
  {
    
      return $this->_tiebreaker1;
  }
  
  
  /**
   * Sets the second tiebreaker
   * @param int $tiebreaker
   * @access public
   * @return Season
   */
  public function setTiebreaker2($tiebreaker)
  {
    $this->_tiebreaker2 = $tiebreaker;
    return $this;
  }

  /**
   * Returns second tiebreaker
   *
   * @access public
   * @return int
   */
  public function getTiebreaker2()
  {
    
      return $this->_tiebreaker2;
  }
  
  
  /**
   * Sets the third tiebreaker
   * @param int $tiebreaker
   * @access public
   * @return Season
   */
  public function setTiebreaker3($tiebreaker)
  {
    $this->_tiebreaker3 = $tiebreaker;
    return $this;
  }

  /**
   * Returns third tiebreaker
   *
   * @access public
   * @return int
   */
  public function getTiebreaker3()
  {
    
      return $this->_tiebreaker3;
  }
  
  
  /**
   * populating data as an array.
   * key of the array is getter methods name. 
   * 
   * @param array $data
   */
  public function populate($data) 
  {
       foreach($data as $key => $value) {
           
           $key = str_replace('_', '',$key);
           $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
       }
       
  }
  
  
  /**
   * usage for creating a NEW season. Provide all neccessary values 
   * in an array. 
   *    
   * @param array $data
   */
    public function exchangeArray($data)
    {
        $this->populate($data);
      
        $this->_year  = new \DateTime();
        
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