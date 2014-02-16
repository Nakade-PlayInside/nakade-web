<?php
namespace League\Statistics;

/**
 * Abstract class for game statistics. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractGameStats 
{
    
    protected $_all_matches;
    
    
    /**
     * setter for entities of matches 
     * @param array $allMatches
     * @return \League\Statistics\AbstractGameStats
     */
    public function setMatches($allMatches) {
        
        $this->_all_matches = $allMatches;
        return $this;
    }
    
    /**
     * getter for matches
     * @return \League\Entities\Matches
     */
    public function getMatches() {
        
        return $this->_all_matches;
    }
    
}

?>
