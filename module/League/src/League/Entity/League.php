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
 * @property int $_divisionId
 * @property string $_title
 * @property int $_ruleId
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
   * League Division
   * bei geteilten Ligen
   *
   * @ORM\Column(name="divisionId", type="integer")
   * @var int
   * @access protected
   */
  protected $_divisionId;
  
  /**
   * Title
   *
   * @ORM\Column(name="title", type="string")
   * @var string
   * @access protected
   */
  protected $_title;

  /**
   * Ruleset Id
   *
   * @ORM\Column(name="ruleId", type="integer")
   * @var int
   * @access protected
   */
   protected $_rulesetId;
  
  /**
   * Sets the Identifier
   *
   * @param int $lid
   * @access public
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
   * @access public
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
   * Sets the Order
   *
   * @param int $order
   * @access public
   * @return League
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
   * Sets the Division
   *
   * @param int $divisionId
   * @access public
   * @return League
   */
  public function setDivision($divisionId)
  {
    $this->_divisionId = $divisionId;
    return $this;
  }

  /**
   * Returns the DevisionId
   *
   * @access public
   * @return string
   */
  public function getDivision()
  {
    return $this->_divisionId;
  }
  
  
  /**
   * Sets the Title
   *
   * @param string $title
   * @access public
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
   * Sets the RulesetId 
   * @param int $rid
   * @access public
   * @return League
   */
  public function setRuleId($rid)
  {
    $this->_rulesetId = $rid;
    return $this;
  }

  /**
   * Returns the RulesetId
   *
   * @access public
   * @return int
   */
  public function getRuleId()
  {
    
      return $this->_rulesetId;
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