<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;
use League\Statistics\Games\WonGames;
use League\Statistics\Games\DrawGames;

/**
 * Calculating the Sum of Opponents Scores. This tiebreaker is almost intended 
 * for tournaments. In a robin-around-league the SOS results in having 
 * all players with the same score. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SOS extends AbstractGameStats implements TiebreakerInterface
{
    
    /**
    * @var AbstractGameStats 
    */
    private static $instance =null;
    
   /**
    * @var name of the tiebreak
    */
    protected $_name        ='SOS';
    
    /**
     * description
     * @var string
     */
    protected $_description ='Sum of Opponent Score';
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new SOS();
        }
        
        return self::$instance;
    }      
    
    /**
     * Name of the Tiebreaker
     * @return string
     */
    public function getName() {
        return $this->_name;
    }
    
    /**
     * Description of the Tiebreaker
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }
    
    /**
     * calculating the points
     * 
     * @param int $playerId
     * @return int
     */
    public function getTieBreaker($playerId) 
    {
       
        $sos=0;
        foreach($this->_all_matches as $match) {
            
            
            if($match->getResultId() ===null            ||
               $match->getResultId()==RESULT::SUSPENDED ) {
                
               continue;
            }    
     
            if($match->getBlackId()==$playerId) {
               
               $opponent = $match->getWhiteId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent);  
               continue;
            }
            
            if($match->getWhiteId()==$playerId) {
               
               $opponent = $match->getBlackId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent); 
               continue;
            }
           
        } 
      
        return $sos;
        
    }
   
    protected function getNumberOfDrawGames($playerId)
    {
        $obj = DrawGames::getInstance();
        $obj->setMatches($this->_all_matches);
        return $obj->getNumberOfGames($playerId);
    }
    
    protected function getNumberOfWonGames($playerId)
    {
        $obj = WonGames::getInstance();
        $obj->setMatches($this->_all_matches);
        return $obj->getNumberOfGames($playerId);
    }
    
}

?>
