<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;

/**
 * Determine the amounts of draw games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class DrawGames extends AbstractGameStats implements GameStatsInterface
{
   /**
    * @var AbstractGameStats 
    */
    private static $instance =null;
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new DrawGames();
        }
        
        return self::$instance;
    }      

    /**
     * gets you the number a draw games
     * @param int $playerId
     * @return int
     */
    public function getNumberOfGames($playerId) 
    {
        
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if($match->getResultId()===null    ||
               $match->getResultId()!=RESULT::DRAW) {
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
