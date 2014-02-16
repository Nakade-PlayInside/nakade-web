<?php
namespace League\Statistics\Tiebreaker;

use RuntimeException;

/**
 * Factory for Tiebreakers. Just provide the tiebreaker and you will receive
 * what you wanted, the tiebreaker points.
 * If you provide an unknown tiebreaker, you will receive an exception. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class TiebreakerFactory {
    
    protected $_Tiebreaker;
    protected $_all_matches;
    protected $_playerId;
    
    /**
     * constructor needs an array of match entities
     * 
     * @param array $allMatches
     */
    public function __construct($allMatches) {
        $this->_all_matches=$allMatches;
    }
    
    /**
     * set the playerId
     * @param int $playerId
     * @return \League\Statistics\Tiebreaker\TiebreakerFactory
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
     * using the switch. 
     * @param string $typ
     * @throws RuntimeException
     */
    protected function setTiebreaker($typ) {
        
        switch (strtolower($typ)) {
           
           case "hahn"  :   $this->_Tiebreaker = HahnPoints::getInstance();
                            break;
               
           case "cuss"  :   $this->_Tiebreaker = CUSS::getInstance();
                            break;
           
           case "sos"   :   $this->_Tiebreaker = SOS::getInstance();
                            break;   
                        
           case "sodos"   :   $this->_Tiebreaker = SODOS::getInstance();
                            break;               
                        
           default      :   throw new RuntimeException(
                sprintf('A unknown tiebreaker was provided: %s', $typ)
           );            
          
        }
        
        
    }
    
    /**
     * returns the name of the provided tiebreaker
     * @param string $typ
     * @return string
     */
    public function getName($typ)
    {
        $this->setTiebreaker($typ);
        return $this->_Tiebreaker->getName();
    }
    
    /**
     * receives the points of the provided tiebreaker
     * @param string $typ
     * @return int
     */
    public function getPoints($typ)
    {
        
        $this->setTiebreaker($typ);
        $this->_Tiebreaker->setMatches($this->_all_matches);
        
        if($this->getPlayerId()==null) {
            throw new RuntimeException(
                sprintf('PlayerId has to be set. Found:null')
            );
        }            
        return $this->_Tiebreaker->getTieBreaker($this->getPlayerId());
    }        
}

?>
