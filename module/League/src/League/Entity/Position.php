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
class Position
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
   protected $_tiebreakerA;
   
   /**
   * Tiebreaker 2
   *
   * @ORM\Column(name="Tiebreaker_2", type="float")
   * @var float
   * @access protected
   */
   protected $_tiebreakerB;
  
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
   * @return Position
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
   * @return Position
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
   * @return Position
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
   * @return Position
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
   * @return Position
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
   * @return Position
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
   * @return Position
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
   * @return Position
   */
  public function setSuspendedGames($nosg)
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
  public function getSuspendedGames()
  {
    return $this->_gamesSuspended;
  }

  /**
   * Sets the Tie-breaker 1
   *
   * @param int $tie
   * @access public
   * @return Position
   */
  public function setTiebreaker1($tie)
  {
    $this->_tiebreakerA = $tie;
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
    return $this->_tiebreakerA;
  }

  /**
   * Sets the Tie-breaker 2
   *
   * @param int $tie
   * @access public
   * @return Position
   */
  public function setTiebreaker2($tie)
  {
    $this->_tiebreakerB = $tie;
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
    return $this->_tiebreakerB;
  }
  
  
  /**
   * Sets the League
   * @param int $league
   * @access public
   * @return Position
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
   * @return Position
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
  
  
  
}