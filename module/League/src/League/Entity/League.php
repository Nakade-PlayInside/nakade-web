<?php

namespace League\Entity;

use League\Entity\Season;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueLigen")
 * @property int $_lid
 * @property int $_sid
 * @property int $_order
 * @property string $_division
 * @property string $_title
 * @property int $_ruleId
 * @property Season $_Season
 */
class League
{
    
    
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var integer
   * @access protected
   */
  protected $_id;

    
  /**
   * Season Identifier
   *
   * @ORM\Column(name="sid", type="integer")
   * @var integer
   * @access protected
   */
  protected $_sid;

  /**
   * League Order
   * eg 1.Liga or 2.Liga 
   *
   * @ORM\Column(name="order", type="integer")
   * @var int
   * @access protected
   */
  protected $_order;
  
  /**
   * League Division
   * bei geteilten Ligen
   *
   * @ORM\Column(name="division", type="string")
   * @var string
   * @access protected
   */
  protected $_division;
  
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
   * Season Entity
   *
   * @ORM\OneToOne(targetEntity="Season")
   * @ORM\JoinColumn(name="sid", referencedColumnName="sid")
   * @var season
   * @access protected
   */
   protected $_season;
   
    
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
  public function setOrder($order)
  {
    $this->_order = $order;
    return $this;
  }

  /**
   * Returns the Order
   *
   * @access public
   * @return int
   */
  public function getOrder()
  {
    return $this->_order;
  }
  
  /**
   * Sets the Division
   *
   * @param string $division
   * @access public
   * @return League
   */
  public function setDivision($division)
  {
    $this->_division = $division;
    return $this;
  }

  /**
   * Returns the Order
   *
   * @access public
   * @return string
   */
  public function getDivision()
  {
    return $this->_division;
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
   * Sets the Season
   * @param int $season
   * @access public
   * @return League
   */
  public function setSeason($season)
  {
    $this->_season = $season;
    return $this;
  } 
  
  /**
   * Returns Season
   *
   * @access public
   * @return Season
   */
  public function getSeason()
  {
    return $this->_season;
  }
  
}