<?php
namespace League\Statistics\Games;

use RuntimeException;

/**
 * This factory provides you the several game stats, e.g. number of 
 * won, lost, draw games etc.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class GamesStatsFactory {
    
    protected $_GameStats;
    protected $_all_matches;
    protected $_playerId;
    
    /**
     * Constructor providing an array of match entities
     * @param array $allMatches
     */
    public function __construct($allMatches) {
        $this->_all_matches=$allMatches;
    }
    
    /**
     * set the playerId
     * @param int $playerId
     * @return \League\Statistics\Games\GamesStatsFactory
     */
    public function setPlayerId($playerId)
    {
        $this->_playerId=$playerId;
        return $this;
    }        
    
    /**
     * get the playerID
     * @return int
     */
    public function getPlayerId()
    {
        return $this->_playerId;
    }        
    
    /**
     * receives the points of the provided game statistics
     * @param string $typ
     * @return string
     * @throws RuntimeException
     */
    public function getPoints($typ)
    {
       
        switch (strtolower($typ)) {
           
           case "played":   $this->_GameStats = PlayedGames::getInstance();
                            break;
               
           case "lost"  :   $this->_GameStats = LostGames::getInstance();
                            break;
                    
           case "won":      $this->_GameStats = WonGames::getInstance();
                            break;
                        
           case "draw":     $this->_GameStats = DrawGames::getInstance();
                            break;
                        
           case "suspended":$this->_GameStats = SuspendedGames::getInstance();
                            break;
                        
           default      :   throw new RuntimeException(
                sprintf('An unknown tiebreaker was provided.')
           );              
        }
        
        
        $this->_GameStats->setMatches($this->_all_matches);
        
         if($this->getPlayerId()==null) {
            throw new RuntimeException(
                sprintf('PlayerId has to be set. Found:null')
            );
        }     
        
        return $this->_GameStats->getNumberOfGames($this->getPlayerId());
    }        
}

?>
