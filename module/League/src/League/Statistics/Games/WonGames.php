<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;


/**
 * Determine the number of won games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class WonGames extends AbstractGameStats implements GameStatsInterface
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
            self::$instance = new WonGames();
        }
        
        return self::$instance;
    }      

    /**
     * gets you the number a won games
     * @param int $playerId
     * @return int
     */
    public function getNumberOfGames($playerId) 
    {
        
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if( $match->getResultId()===null        ||
                $match->getResultId()==RESULT::DRAW || 
                $match->getResultId()==RESULT::SUSPENDED ) {
                
                continue;
            }    
   
            if($match->getWinnerId()==$playerId) {
                $count++;
            }    
            
        } 
      
        return $count;
    }
   
    
}

?>
