<?php
namespace League\Entity;

use League\Entity\League;
use User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leaguePositions")
 * @property int $_lid
 * @property int $_uid
 * @property int $_gamesPlayed
 * @property int $_win
 * @property int $_loss
 * @property int $_jigo
 * @property int $_gamesSuspended
 * @property decimal $_tiebreaker1
 * @property decimal $_tiebreaker2
 * @property League $_League
 * @property User $_Player
 */
class Table
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
   * User Id: Foreign Key
   *
   * @ORM\Column(name="uid", type="integer")
   * @var int
   * @access protected
   */
  protected $_uid;
  
  /**
   * Games Played
   *
   * @ORM\Column(name="GP", type="integer")
   * @var int
   * @access protected
   */
  protected $_gamesPlayed;
  
  /**
   * Wins
   *
   * @ORM\Column(name="W", type="integer")
   * @var int
   * @access protected
   */
  protected $_win;

  /**
   * Losses
   *
   * @ORM\Column(name="L", type="integer")
   * @var int
   * @access protected
   */
   protected $_loss;
   
   /**
   * Jigo
   *
   * @ORM\Column(name="J", type="integer")
   * @var int
   * @access protected
   */
   protected $_jigo;
   
   /**
   * Games Suspended
   *
   * @ORM\Column(name="GS", type="integer")
   * @var int
   * @access protected
   */
   protected $_gamesSuspended;
   
   /**
   * Tiebreaker 1
   *
   * @ORM\Column(name="Tiebreaker_1", type="float")
   * @var float
   * @access protected
   */
   protected $_tiebreaker1;
   
   /**
   * Tiebreaker 2
   *
   * @ORM\Column(name="Tiebreaker_2", type="float")
   * @var float
   * @access protected
   */
   protected $_tiebreaker2;
  
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
   * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
   * @var User
   * @access protected
   */
   protected $_player;
   
   
  /**
   * Sets the Identifier
   *
   * @param int $pid
   * @access public
   * @return Table
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
   * @return Table
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
   * Sets the uid
   *
   * @param int $uid
   * @access public
   * @return Table
   */
  public function setUid($uid)
  {
    $this->_uid = $uid;
    return $this;
  }

  /**
   * Returns the uid
   *
   * @access public
   * @return int
   */
  public function getUid()
  {
    return $this->_uid;
  }
  
  /**
   * Sets the games played
   *
   * @param int $nogp
   * @access public
   * @return Table
   */
  public function setGamesPlayed($nogp)
  {
    $this->_gamesPlayed = $nogp;
    return $this;
  }

  /**
   * Returns the games played
   *
   * @access public
   * @return int
   */
  public function getGamesPlayed()
  {
    return $this->_gamesPlayed;
  }
  
  /**
   * Sets the wins
   *
   * @param int $now
   * @access public
   * @return Table
   */
  public function setWin($now)
  {
    $this->_win = $now;
    return $this;
  }

  /**
   * Returns the wins
   *
   * @access public
   * @return int
   */
  public function getWin()
  {
    return $this->_win;
  }
  
  /**
   * Sets the lost games
   *
   * @param int $nol
   * @access public
   * @return Table
   */
  public function setLoss($nol)
  {
    $this->_loss = $nol;
    return $this;
  }

  /**
   * Returns the lost games
   *
   * @access public
   * @return int
   */
  public function getLoss()
  {
    return $this->_loss;
  }
  
  /**
   * Sets the Number of Jigo
   *
   * @param int $noj
   * @access public
   * @return Table
   */
  public function setJigo($noj)
  {
    $this->_jigo = $noj;
    return $this;
  }

  /**
   * Returns the Number of Jigo
   *
   * @access public
   * @return int
   */
  public function getJigo()
  {
    return $this->_jigo;
  }

  /**
   * Sets the Number of suspended Games
   *
   * @param int $noj
   * @access public
   * @return Table
   */
  public function setGamesSuspended($nosg)
  {
    $this->_gamesSuspended = $nosg;
    return $this;
  }

  /**
   * Returns the No of suspended Games
   *
   *
   * @access public
   * @return int
   */
  public function getGamesSuspended()
  {
    return $this->_gamesSuspended;
  }

  /**
   * Sets the Tie-breaker 1
   *
   * @param int $tie
   * @access public
   * @return Table
   */
  public function setTiebreaker1($tie)
  {
    $this->_tiebreaker1 = $tie;
    return $this;
  }

  /**
   * Returns the Tie-breaker 1
   *
   * @access public
   * @return int
   */
  public function getTiebreaker1()
  {
    return $this->_tiebreaker1;
  }

  /**
   * Sets the Tie-breaker 2
   *
   * @param int $tie
   * @access public
   * @return Table
   */
  public function setTiebreaker2($tie)
  {
    $this->_tiebreaker2 = $tie;
    return $this;
  }

  /**
   * Returns the Tie-breaker 2
   *
   * @access public
   * @return int
   */
  public function getTiebreaker2()
  {
    return $this->_tiebreaker2;
  }
  
  
  /**
   * Sets the League
   * @param int $league
   * @access public
   * @return Table
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
   * Sets the Player
   * @param int $uid
   * @access public
   * @return Table
   */
  public function setPlayer($uid)
  {
    $this->_player = $uid;
    return $this;
  } 
  
  /**
   * Returns Player
   *
   * @access public
   * @return User
   */
  public function getPlayer()
  {
    return $this->_player;
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
    
   /**
    * Populate from an array.
    * Provide all values by its absolute value, e.g. if subtracted
    * value have to be minus.
    *
    * @param array $data
    */
    public function populate($data = array()) 
    {
        
        //game played
        $this->_gamesPlayed += isset($data['gamesPlayed'])? 
            $data['gamesPlayed']:0;
                
        //game suspended
        $this->_gamesSuspended += isset($data['gamesSuspended'])?
            $data['gamesSuspended']:0;
        
        //jigo
        $this->_jigo += isset($data['jigo'])?
            $data['jigo']:0;
        
        //win
        $this->_win += isset($data['win'])?
            $data['win']:0;
        
        //loss
        $this->_loss += isset($data['loss'])?
            $data['loss']:0;
        
        //tiebreaker1
        $this->_tiebreaker1 += isset($data['tiebreaker1'])?
            $data['tiebreaker1']:0;
        
        //tiebreaker2
        $this->_tiebreaker2 += isset($data['tiebreaker2'])?
            $data['tiebreaker2']:0;
    }
  
  
}