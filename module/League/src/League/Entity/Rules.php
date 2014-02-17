<?php

namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueRules")
 * @property int $_id
 * @property int $_draw
 * @property int $_drawPoints
 * @property int $_winPoints
 * @property string $_tiebreaker1
 * @property string $_tiebreaker2
 * @property string $_tiebreaker3
 */
class Rules
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
   * points for a draw
   *
   * @ORM\Column(name="drawPoints", type="integer")
   * @var int
   * @access protected
   */
  protected $_drawPoints;
  
  /**
   * points for a win
   *
   * @ORM\Column(name="winPoints", type="integer")
   * @var int
   * @access protected
   */
  protected $_winPoints;

  /**
   * first tiebreaker
   *
   * @ORM\Column(name="tiebreaker1", type="string")
   * @var string
   * @access protected
   */
   protected $_tiebreaker1;
  
   /**
   * second tiebreaker
   *
   * @ORM\Column(name="tiebreaker2", type="string")
   * @var string
   * @access protected
   */
   protected $_tiebreaker2;
   
   /**
   * third tiebreaker
   *
   * @ORM\Column(name="tiebreaker3", type="string")
   * @var string
   * @access protected
   */
   protected $_tiebreaker3;
   
  /**
   * Sets the Identifier
   *
   * @param int $lid
   * @access public
   * @return Rules
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
   * Sets the points for a draw
   *
   * @param int $points
   * @access public
   * @return Rules
   */
  public function setDrawPoints($points)
  {
    $this->_drawPoints = $points;
    return $this;
  }

  /**
   * Returns the points for a draw
   *
   * @access public
   * @return int
   */
  public function getDrawPoints()
  {
    return $this->_drawPoints;
  }
  
  /**
   * Sets the points for a win
   *
   * @param int $points
   * @access public
   * @return Rules
   */
  public function setWinPoints($points)
  {
    $this->_winPoints = $points;
    return $this;
  }

  /**
   * Returns the points for a win
   *
   * @access public
   * @return int
   */
  public function getWinPoints()
  {
    return $this->_winPoints;
  }
  
  
  /**
   * Sets the first Tiebreaker
   *
   * @param string $tiebreaker
   * @access public
   * @return Rules
   */
  public function setTiebreaker1($tiebreaker)
  {
    $this->_tiebreaker1 = $tiebreaker;
    return $this;
  }

  /**
   * Returns the first Tiebreaker
   *
   * @access public
   * @return string
   */
  public function getTiebreaker1()
  {
    return $this->_tiebreaker1;
  }

  /**
   * Sets the second Tiebreaker
   *
   * @param string $tiebreaker
   * @access public
   * @return Rules
   */
  public function setTiebreaker2($tiebreaker)
  {
    $this->_tiebreaker2 = $tiebreaker;
    return $this;
  }

  /**
   * Returns the second Tiebreaker
   *
   * @access public
   * @return string
   */
  public function getTiebreaker2()
  {
    return $this->_tiebreaker2;
  }
  
  
  /**
   * Sets the third Tiebreaker
   *
   * @param string $tiebreaker
   * @access public
   * @return Rules
   */
  public function setTiebreaker3($tiebreaker)
  {
    $this->_tiebreaker3 = $tiebreaker;
    return $this;
  }

  /**
   * Returns the third Tiebreaker
   *
   * @access public
   * @return string
   */
  public function getTiebreaker3()
  {
    return $this->_tiebreaker3;
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