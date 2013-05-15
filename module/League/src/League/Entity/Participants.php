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
   * User Entity
   *
   * @ORM\OneToOne(targetEntity="User\Entity\User")
   * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
   * @var User
   * @access protected
   */
   protected $_player;
  
   protected $_games_played;
   protected $_games_suspended;
   protected $_games_win;
   protected $_games_lost;
   protected $_games_draw;
   protected $_games_points;
   protected $_first_tiebreak;
   protected $_second_tiebreak;
   
   
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
  
  /*****************************/
  public function populate($data) 
  {
       foreach($data as $key => $value) {
            
           $method = 'set'.ucfirst($key);
       
            if(method_exists($this, $method))
                $this->$method($value);
       }
       
  }
  
  
  public function setGamesPlayed($noGames)
  {
    $this->_games_played = $noGames;
    return $this;
  } 
  
  public function getGamesPlayed()
  {
    return $this->_games_played;
  }
  
  public function setGamesSuspended($noGames)
  {
    $this->_games_suspended = $noGames;
    return $this;
  } 
  
  public function getGamesSuspended()
  {
    return $this->_games_suspended;
  }
  
  public function setGamesDraw($noGames)
  {
    $this->_games_draw = $noGames;
    return $this;
  } 
  
  public function getGamesDraw()
  {
    return $this->_games_draw;
  }
  
  public function setGamesWin($noGames)
  {
    $this->_games_win = $noGames;
    return $this;
  } 
  
  public function getGamesWin()
  {
    return $this->_games_win;
  }
  
  public function setGamesLost($noGames)
  {
    $this->_games_lost = $noGames;
    return $this;
  } 
  
  public function getGamesLost()
  {
    return $this->_games_lost;
  }
  
  public function setGamesPoints($points)
  {
    $this->_games_points = $points;
    return $this;
  } 
  
  public function getGamesPoints()
  {
    return $this->_games_points;
  }
  
  public function setFirstTiebreak($points)
  {
    $this->_first_tiebreak = $points;
    return $this;
  } 
  
  public function getFirstTiebreak()
  {
    return $this->_first_tiebreak;
  }
  
  public function setSecondTiebreak($points)
  {
    $this->_second_tiebreak = $points;
    return $this;
  } 
  
  public function getSecondTiebreak()
  {
    return $this->_second_tiebreak;
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