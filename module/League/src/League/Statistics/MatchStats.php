<?php
namespace League\Statistics;

use League\Statistics\Tiebreaker\TiebreakerFactory;
use League\Statistics\Games\GamesStatsFactory;

/**
 * Determining the games stats for having a sorted table of
 * a league.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchStats 
{
    protected $_GamesStatsFactory;
    protected $_TiebreakerFactory;
    protected $_players;
    protected $_matches; 
    protected $_points_for_win=1;
    protected $_points_for_draw=0;
    protected $_first_tiebreak ='Hahn';
    protected $_second_tiebreak='SODOS';
    protected $_third_tiebreak ='CUSS';
    
    /**
     * setter TiebreakerFactory
     * @param TiebreakerFactory $factory
     * @return \League\Statistics\MatchStats
     */
    public function setTiebreakerFactory($factory)
    {
        $this->_TiebreakerFactory = $factory;
        return $this;
    }
    
    /**
     * getter TiebreakerFactory
     * @return TiebreakerFactory
     */
    public function getTiebreakerFactory()
    {
        if($this->_TiebreakerFactory==null) {
            
            $this->_TiebreakerFactory = new TiebreakerFactory(
                    $this->_matches
            );
        }
        return $this->_TiebreakerFactory;
    }
    
    /**
     * setter GamesStats Factory
     * @param GamesStatsFactory $factory
     * @return \League\Statistics\MatchStats
     */
    public function setGamesStatsFactory($factory)
    {
        $this->_GamesStatsFactory = $factory;
        return $this;
    }
    
    /**
     * getter GamesStats Factory
     * @return GamesStatsFactory
     */
    public function getGamesStatsFactory()
    {
        if($this->_GamesStatsFactory==null) {
        
            $this->_GamesStatsFactory = new GamesStatsFactory(
                    $this->_matches
            );
        }
        
        return $this->_GamesStatsFactory;
    }
            
    /**
     * Expecting an array of rules with a leading underline it will 
     * populate the rules, i.e. points for wins, draws and which 
     * tiebreakers are taken into account
     *  
     * @param array $rules
     */
    public function populateRules($rules)
    {
        foreach($rules as $key=>$value) {
            
            $method = 'set'. ucfirst(str_replace('_', '',$key));
            
            if(method_exists($this, $method) && isset($value)) {
               $this->$method($value);
            }    
        }
       
    } 
    
    /**
     * Set the points a player receives for winning
     * @param int $points
     * @return \League\Statistics\MatchStats
     */
    public function setWinPoints($points)
    {
        $this->_points_for_win = $points;
        return $this;
    } 
  
    /**
     * Get the points a player receives for winning
     * @return int
     */
    public function getWinPoints()
    {
        return $this->_points_for_win;
    }
    
    /**
     * Set the points a player receives for a draw
     * @param int $points
     * @return \League\Statistics\MatchStats
     */
    public function setDrawPoints($points)
    {
        $this->_points_for_draw = $points;
        return $this;
    } 
  
    /**
     * Get the points a player receives for a draw
     * @return int
     */
    public function getDrawPoints()
    {
        return $this->_points_for_draw;
    }
    
    /**
     * Set the first tiebreaker
     * @param string $name
     * @return \League\Statistics\MatchStats
     */
    public function setTiebreaker1($name)
    {
        $this->_first_tiebreak = $name;
        return $this;
    } 
  
    /**
     * Get the first tiebreaker
     * @return string
     */
    public function getTiebreaker1()
    {
        return $this->_first_tiebreak;
    }
    
    /**
     * Set the second tiebreaker
     * @param string $name
     * @return \League\Statistics\MatchStats
     */
    public function setTiebreaker2($name)
    {
        $this->_second_tiebreak = $name;
        return $this;
    } 
  
    /**
     * Get the second tiebreaker
     * @return string
     */
    public function getTiebreaker2()
    {
        return $this->_second_tiebreak;
    }
    
    /**
     * Set the third tiebreaker
     * @param string $name
     * @return \League\Statistics\MatchStats
     */
    public function setTiebreaker3($name)
    {
        $this->_third_tiebreak = $name;
        return $this;
    } 
  
    /**
     * Get the third tiebreaker
     * @return string
     */
    public function getTiebreaker3()
    {
        return $this->_third_tiebreak;
    }
  
    /**
     * set an array of match entites
     * @param array $allMatches
     * @return \League\Statistics\MatchStats
     */
    public function setMatches($allMatches)
    {
        $this->_matches = $allMatches;
        return $this;
    } 
  
    /**
     * get an array of match entites
     * @return array
     */
    public function getMatches()
    {
        return $this->_matches;
    }
    
    /**
     * set the players array
     * @param array $playersInLeague
     * @return \League\Statistics\MatchStats
     */
    public function setPlayers($playersInLeague)
    {
        $this->_players = $playersInLeague;
        return $this;
    }
    
    /**
     * get the players 
     * @return array
     */
    public function getPlayers()
    {
        return $this->_players;
    }
    
    /**
     * Determine the games stats and tiebreakers of all players in a league.
     * Therefore, a result table is returned sorted by points by default.
     * You can choose the first sorting...  
     * @param string $sort
     * @return array of players
     */
    public function getMatchStats()
    {
        
        $players = $this->getPlayers();
        
        //putting the calculated stats into the players
        for ($i = 0; $i < count($players); ++$i) {
           $player =  $players[$i];
           $data = $this->getPlayersStats($player->getUid());
           $player->populate($data);
        }
       
        return $players;
    }
    
    protected function getPlayersStats($playerId) 
    {
        $this->getGamesStatsFactory()->setPlayerId($playerId);
        $this->getTiebreakerFactory()->setPlayerId($playerId);
                
        $results = array(
           'gamesPlayed'    => $this->getGamesStatsFactory()
                                    ->getPoints('played'),
            
           'gamesSuspended' => $this->getGamesStatsFactory()
                                    ->getPoints('suspended'),
            
           'gamesWin'       => $this->getGamesStatsFactory()
                                    ->getPoints('won'),
            
           'gamesDraw'      => $this->getGamesStatsFactory()
                                    ->getPoints('draw'),
            
           'gamesLost'      => $this->getGamesStatsFactory()
                                    ->getPoints('lost'),
            
           'gamesPoints'    => $this->getPoints(),
            
           'firstTiebreak'  => $this->getTiebreakerFactory()
                                    ->getPoints($this->_first_tiebreak),
            
           'secondTiebreak' => $this->getTiebreakerFactory()
                                    ->getPoints($this->_second_tiebreak),
            
           'thirdTiebreak' => $this->getTiebreakerFactory()
                                    ->getPoints($this->_third_tiebreak),
          
        );
        
        return $results;
    }
    
    protected function getPoints() 
    {
        return  $this->getGamesStatsFactory()
                     ->getPoints('won')  * $this->getWinPoints() +
                $this->getGamesStatsFactory()
                     ->getPoints('draw') * $this->getDrawPoints();
    }
    
    

}

?>
