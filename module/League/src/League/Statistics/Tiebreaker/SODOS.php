<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;
use League\Statistics\Games\WonGames;
use League\Statistics\Games\DrawGames;

/**
 * Calculating the Sum of defeated Opponents Scores.  
 * This tiebreaker is suited best as a third tiebreaker since the only 
 * resulting difference is between successful players. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SODOS extends AbstractGameStats implements TiebreakerInterface
{
    
    /**
    * @var AbstractGameStats 
    */
    private static $instance =null;
    
   /**
    * @var name of the tiebreak
    */
    protected $_name        ='SODOS';
    
    /**
     * description
     * @var string
     */
    protected $_description ='Sum of defeated Opponents Score';
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new SODOS();
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
               $match->getResultId()==RESULT::SUSPENDED ||
               $match->getResultId()==RESULT::DRAW     
            ) {
               continue;
            }    
     
            if($match->getWinnerId()==$playerId    && 
               $match->getBlackId()==$playerId ) {
               
               $opponent = $match->getWhiteId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent);  
               continue;
            }
            
            if($match->getWinnerId()==$playerId    && 
               $match->getWhiteId()==$playerId) {
               
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
