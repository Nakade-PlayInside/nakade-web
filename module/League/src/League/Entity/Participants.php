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
    * properties for games stats.
    * being set during match stat analysis
    * @var int 
    */
   protected $_games_played;
   protected $_games_suspended;
   protected $_games_win;
   protected $_games_lost;
   protected $_games_draw;
   protected $_games_points;
   protected $_first_tiebreak;
   protected $_second_tiebreak;
   protected $_third_tiebreak;
     
    
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
  
  /**
   * set number of games played
   * @param int $noGames
   * @return \League\Entity\Participants
   */
  public function setGamesPlayed($noGames)
  {
    $this->_games_played = $noGames;
    return $this;
  } 
  
  /**
   * get number of played games
   * @return int
   */
  public function getGamesPlayed()
  {
    return $this->_games_played;
  }
  
  /**
   * setter for number of suspended games
   * 
   * @param int $noGames
   * @return \League\Entity\Participants
   */
  public function setGamesSuspended($noGames)
  {
    $this->_games_suspended = $noGames;
    return $this;
  } 
  
  /**
   * getter for number of suspended games
   * 
   * @return int
   */
  public function getGamesSuspended()
  {
    return $this->_games_suspended;
  }
  
  /**
   * setter for number of draw games
   * 
   * @param int $noGames
   * @return \League\Entity\Participants
   */
  public function setGamesDraw($noGames)
  {
    $this->_games_draw = $noGames;
    return $this;
  } 
  
  /**
   * getter for number of draw games
   * 
   * @return int
   */
  public function getGamesDraw()
  {
    return $this->_games_draw;
  }
  
  /**
   * setter for number of won games
   * 
   * @param int $noGames
   * @return \League\Entity\Participants
   */
  public function setGamesWin($noGames)
  {
    $this->_games_win = $noGames;
    return $this;
  } 
  
  /**
   * getter for number of won games
   * 
   * @return int
   */
  public function getGamesWin()
  {
    return $this->_games_win;
  }
  
  /**
   * setter for number of lost games
   * 
   * @param int $noGames
   * @return \League\Entity\Participants
   */
  public function setGamesLost($noGames)
  {
    $this->_games_lost = $noGames;
    return $this;
  } 
  
  /**
   * getter for number of lost games
   * 
   * @return int
   */
  public function getGamesLost()
  {
    return $this->_games_lost;
  }
  
  /**
   * setter for points 
   * 
   * @param int $points
   * @return \League\Entity\Participants
   */  
  public function setGamesPoints($points)
  {
    $this->_games_points = $points;
    return $this;
  } 
  
  /**
   * getter for points
   * 
   * @return int
   */
  public function getGamesPoints()
  {
    return $this->_games_points;
  }
  
  /**
   * setter of first tiebreak
   * 
   * @param int $points
   * @return \League\Entity\Participants
   */
  public function setFirstTiebreak($points)
  {
    $this->_first_tiebreak = $points;
    return $this;
  } 
  
  /**
   * getter of first tiebreak
   * 
   * @return int
   */
  public function getFirstTiebreak()
  {
    return $this->_first_tiebreak;
  }
  
  /**
   * setter of second tiebreak
   * 
   * @param int $points
   * @return \League\Entity\Participants
   */
  public function setSecondTiebreak($points)
  {
    $this->_second_tiebreak = $points;
    return $this;
  } 
  
  /**
   * getter of second tiebreak
   * 
   * @return int
   */
  public function getSecondTiebreak()
  {
    return $this->_second_tiebreak;
  }
  
  /**
   * setter of third tiebreak
   * 
   * @param int $points
   * @return \League\Entity\Participants
   */
  public function setThirdTiebreak($points)
  {
    $this->_third_tiebreak = $points;
    return $this;
  } 
  
  /**
   * getter of third tiebreak
   * 
   * @return int
   */
  public function getThirdTiebreak()
  {
    return $this->_third_tiebreak;
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
 
  
}