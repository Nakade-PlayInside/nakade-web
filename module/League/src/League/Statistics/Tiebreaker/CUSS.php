<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;

/**
 * Calculating the Cummulative Sum of Scores which is the
 * sum of a player's points after each round (matchday).
 * This can break fraud by loosing with intent. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class CUSS extends AbstractGameStats implements TiebreakerInterface
{
    
    /**
    * @var AbstractGameStats 
    */
    private static $instance =null;
    
   /**
    * @var name of the tiebreak
    */
    protected $_name        ='CUSS';
    
    /**
     * description
     * @var string
     */
    protected $_description ='Cummulative Sum of Scores';
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new CUSS();
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
       
        $count=0;
        $cuss=0;
        foreach($this->_all_matches as $match) {
            
            
            if($match->getResultId() ===null            ||
               $match->getResultId()==RESULT::SUSPENDED ) {
                
               continue;
            }    
     
            if($match->getWinnerId()==$playerId    ||
               $match->getResultId()==RESULT::DRAW ) {
                
               $count++; 
               $cuss += $count;
               continue;
            }
            
            $cuss += $count;
        } 
      
        return $cuss;
        
    }
   
    
}

?>
