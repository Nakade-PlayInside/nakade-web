<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;

/**
 * Determine the number of played games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PlayedGames extends AbstractGameStats implements GameStatsInterface
{
    /**
    * @var AbstractGameStats 
    */
    public static $instance =null;
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
      
        if(self::$instance == null) {
            self::$instance = new PlayedGames();
        }
        
        return self::$instance;
    }      

    /**
     * gets you the number a played games
     * @param int $playerId
     * @return int
     */
    public function getNumberOfGames($playerId) 
    {
        
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if($match->getResultId() ===null    ||
               $match->getResultId()==RESULT::SUSPENDED) {
               continue;
            }    
     
            if( $match->getBlackId()==$playerId || 
                $match->getWhiteId()==$playerId ) {
                
                $count++;
            }    
            
        } 
      
        return $count;
        
    }
   
    
}

?>
