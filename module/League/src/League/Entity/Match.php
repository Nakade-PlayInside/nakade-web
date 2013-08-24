<?php
namespace League\Entity;


use League\Entity\League;
use User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a Match
 *
 * @ORM\Entity
 * @ORM\Table(name="leaguePairings")
 * @property int $_id
 * @property int $_lid
 * @property int $_blackId
 * @property int $_whiteId
 * @property int $_resultId 
 * @property int $_winnerId
 * @property decimal $_points
 * @property DateTime $_date
 * @property League $_League
 * @property User $_Black
 * @property User $_White
 * @property User $_Winner
 * @property Result $_Result
 * @property Result $_matchday
 */
class Match
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
   * League Identifier: Foreign Key
   *
   * @ORM\Column(name="lid", type="integer")
   * @var integer
   * @access protected
   */
  protected $_lid;

  /**
   * Black Id: Foreign Key
   *
   * @ORM\Column(name="black", type="integer")
   * @var int
   * @access protected
   */
  protected $_blackId;
  
  /**
   * White Id: Foreign Key
   *
   * @ORM\Column(name="white", type="integer")
   * @var int
   * @access protected
   */
  protected $_whiteId;
  
  /**
   * Result Id: Foreign Key
   *
   * @ORM\Column(name="resultId", type="integer")
   * @var int
   * @access protected
   */
  protected $_resultId;

  /**
   * Winner Id: Foreign Key
   *
   * @ORM\Column(name="winner", type="integer")
   * @var int
   * @access protected
   */
   protected $_winnerId;
   
   /**
   * points
   *
   * @ORM\Column(name="points", type="float")
   * @var float
   * @access protected
   */
   protected $_points;
   
   
   /**
   * Date
   *
   * @ORM\Column(name="date", type="datetime")
   * @var DateTime
   * @access protected
   */
   protected $_date;
  
   /**
   * League Entity
   *
   * @ORM\OneToOne(targetEntity="League")
   * @ORM\JoinColumn(name="lid", referencedColumnName="id")
   * @var League
   * @access protected
   */
   protected $_league;
   
   /**
   * User Entity
   *
   * @ORM\OneToOne(targetEntity="User\Entity\User")
   * @ORM\JoinColumn(name="black", referencedColumnName="uid")
   * @var User
   * @access protected
   */
   protected $_black;
   
   /**
   * User Entity
   *
   * @ORM\OneToOne(targetEntity="User\Entity\User")
   * @ORM\JoinColumn(name="white", referencedColumnName="uid")
   * @var User
   * @access protected
   */
   protected $_white;
   
   /**
   * User Entity
   *
   * @ORM\OneToOne(targetEntity="User\Entity\User")
   * @ORM\JoinColumn(name="winner", referencedColumnName="uid")
   * @var User
   * @access protected
   */
   protected $_winner;
   
   
    /**
   * matchday 
   *
   * @ORM\Column(name="matchday", type="integer")
   * @var int
   * @access protected
   */
   protected $_matchday;
   
  /**
   * Sets the Identifier
   *
   * @param int $pid
   * @access public
   * @return Match
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
   * Sets the blackId
   *
   * @param int $order
   * @access public
   * @return Match
   */
  public function setBlackId($bid)
  {
    $this->_blackId = $bid;
    return $this;
  }

  /**
   * Returns the blackId
   *
   * @access public
   * @return int
   */
  public function getBlackId()
  {
    return $this->_blackId;
  }
  
  /**
   * Sets the WhiteId
   *
   * @param int $wid
   * @access public
   * @return Match
   */
  public function setWhiteId($wid)
  {
    $this->_whiteId = $wid;
    return $this;
  }

  /**
   * Returns the WhiteId
   *
   * @access public
   * @return int
   */
  public function getWhiteId()
  {
    return $this->_whiteId;
  }
  
  /**
   * Sets the resultId
   *
   * @param int $rid
   * @access public
   * @return Match
   */
  public function setResultId($rid)
  {
    $this->_resultId = $rid;
    return $this;
  }

  /**
   * Returns the resultId
   *
   * @access public
   * @return int
   */
  public function getResultId()
  {
    return $this->_resultId;
  }
  
  /**
   * Sets the winnerId
   *
   * @param int $wid
   * @access public
   * @return Match
   */
  public function setWinnerId($wid)
  {
    $this->_winnerId = $wid;
    return $this;
  }

  /**
   * Returns the winnerId
   *
   * @access public
   * @return int
   */
  public function getWinnerId()
  {
    return $this->_winnerId;
  }
  
  /**
   * Sets the Points
   *
   * @param float $points
   * @access public
   * @return Match
   */
  public function setPoints($points)
  {
    $this->_points = $points;
    return $this;
  }

  /**
   * Returns the Points
   *
   * @access public
   * @return float
   */
  public function getPoints()
  {
    return $this->_points;
  }

  
  /**
   * Sets the Date
   *
   * @param DateTime $date
   * @access public
   * @return Match
   */
  public function setDate($date)
  {
    $this->_date = $date;
    return $this;
  }

  /**
   * Returns the Date
   *
   * @access public
   * @return DateTime
   */
  public function getDate()
  {
    return $this->_date;
  }
  
  /**
   * Sets the League
   * @param int $league
   * @access public
   * @return Match
   */
  public function setLeague($league)
  {
    $this->_league = $league;
    return $this;
  } 
  
  /**
   * Returns League
   *
   * @access public
   * @return League
   */
  public function getLeague()
  {
    return $this->_league;
  }
  
  /**
   * Sets the black User
   * @param int $uid
   * @access public
   * @return Match
   */
  public function setBlack($uid)
  {
    $this->_black = $uid;
    return $this;
  } 
  
  /**
   * Returns Black User
   *
   * @access public
   * @return User
   */
  public function getBlack()
  {
    return $this->_black;
  }
  
  /**
   * Sets the white User
   * @param int $uid
   * @access public
   * @return Match
   */
  public function setWhite($uid)
  {
    $this->_white = $uid;
    return $this;
  } 
  
  /**
   * Returns white User
   *
   * @access public
   * @return User
   */
  public function getWhite()
  {
    return $this->_white;
  }
  
  /**
   * Sets the winning User
   * @param int $uid
   * @access public
   * @return Match
   */
  public function setWinner($uid)
  {
    $this->_winner = $uid;
    return $this;
  } 
  
  /**
   * Returns winning User
   *
   * @access public
   * @return User
   */
  public function getWinner()
  {
    return $this->_winner;
  }
  
  
  
  /**
   * Sets the Matchday
   *
   * @param int $noday
   * @access public
   * @return Match
   */
  public function setMatchday($noday)
  {
    $this->_matchday = $noday;
    return $this;
  }

  /**
   * Returns the Matchday
   *
   * @access public
   * @return int
   */
  public function getMatchday()
  {
    return $this->_matchday;
  }
  
  public function getTime()
  {
      return $this->_date->format('H:i:s');
  }
  
  /**
   * usage for creating a NEW match. Provide all neccessary values 
   * in an array. 
   *    
   * @param array $data
   */
    public function exchangeArray($data)
    {
        foreach($data as $key => $value) {
           $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
        }
       
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
 
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array()) 
    {
        
        
        $this->_id       = $data['pid'];
        $this->_resultId = $data['result'];
        $this->_winnerId = $data['winner'];
        $this->_points   = $data['points'];
             
    }
 
  
}