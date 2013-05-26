<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;

/**
 * Determine the number of suspended games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SuspendedGames extends AbstractGameStats implements GameStatsInterface
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
            self::$instance = new SuspendedGames();
        }
        
        return self::$instance;
    }      

    /**
     * gets you the number a suspended games
     * @param int $playerId
     * @return int
     */
    public function getNumberOfGames($playerId) 
    {
        
        $count=0;
        foreach($this->_all_matches as $match) {
            
            $blackId = $match->getBlackId();
            $whiteId = $match->getWhiteId();
            
            if($match->getResultId() ===null ||
               $match->getResultId()!=RESULT::SUSPENDED ) {
                continue;
            }    
     
            if($blackId==$playerId || $whiteId==$playerId) {
                $count++;
            }    
            
        } 
      
        return $count;
    }
   
    
}

?>
