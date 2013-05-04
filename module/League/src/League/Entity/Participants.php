<?php
namespace League\Entity;

use League\Entity\League;
use User\Entity\User;
use League\Entity\Season;
use Doctrine\ORM\Mapping as ORM;


/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueParticipants")
 * @property int $_lid
 * @property int $_id
 * @property int $_sid
 * @property int $_uid 
 */
class Participants 
{
   
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="pid", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var integer
   * @access protected
   */
  protected $_id;

    
  /**
   * League Identifier: Foreign Key
   *
   * @ORM\Column(name="lid", type="integer")
   * @var integer
   * @access protected
   */
  protected $_lid;

  /**
   * Season Id: Foreign Key
   *
   * @ORM\Column(name="sid", type="integer")
   * @var int
   * @access protected
   */
  protected $_sid;
  
  /**
   * User Id: Foreign Key
   *
   * @ORM\Column(name="uid", type="integer")
   * @var int
   * @access protected
   */
  protected $_uid;

  
  /**
   * Sets the Identifier
   *
   * @param int $pid
   * @access public
   * @return Pairings
   */
  public function setId($pid)
  {
    $this->_id = $pid;
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
   * Sets the LeagueId
   *
   * @param int $lid
   * @access public
   * @return League
   */
  public function setLid($lid)
  {
    $this->_lid = $lid;
    return $this;
  }

  /**
   * Returns the LeagueId
   *
   * @access public
   * @return int
   */
  public function getLid()
  {
    return $this->_lid;
  }


  /**
   * Sets the SeasonId
   *
   * @param int $sid
   * @access public
   * @return Season
   */
  public function setSid($sid)
  {
    $this->_sid = $sid;
    return $this;
  }

  /**
   * Returns the seasonId
   *
   * @access public
   * @return int
   */
  public function getSid()
  {
    return $this->_sid;
  }
  
  /**
   * Sets UserId
   *
   * @param int $uid
   * @access public
   * @return User
   */
  public function setUid($uid)
  {
    $this->_uid = $uid;
    return $this;
  }

  /**
   * Returns uid
   *
   * @access public
   * @return int
   */
  public function getUid()
  {
    return $this->_uid;
  }
  
  
   /**
    * Magic getter to expose protected properties.
    *
    * @param string $property
    * @return mixed
    */
    public function __get($property) 
    {
        return $this->$property;
    }
 
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
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