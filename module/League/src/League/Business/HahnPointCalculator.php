<?php
namespace League\Business;

/**
 * Calculating Hahn-Points 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class HahnPointCalculator implements calcPointsInterface {
    
    protected $_max_points;
    protected $_skip_points=0;
    protected $_offset_points;

    /**
     * Constructor awaiting an offset, a maximum and optional points for 
     * suspending (skipping) a match. The offset is the number of points
     * where both players get points. If a match result is over offset or 
     * won by resignation, the maximum points are given to the winner 
     * while the looser is getting no point at all. 
     * Points are floats. Half points are possible. 
     * 
     * @param float $offset
     * @param float $max
     * @param float $skip
     */
    public function __construct($offset, $max, $skip=null) {
        
        if(isset($offset))
            $this->_offset_points = floatval ($offset);
        
        if(isset($max))
            $this->_max_points = floatval ($max);
        
        if(isset($skip))
            $this->_skip_points = floatval ($skip);
        
    }
    
   
    public function getPointsForResign()
    {
        return floatval($this->_max_points);
    }
    
    
    public function getPointsForSkip()
    {
        return floatval($this->_skip_points);
    }
    
    
    public function getPointsForWin($points)
    {
        if(floatval($points) >= $this->_offset_points)
            return $this->getPointsForResign ();
        
        return floatval($this->_offset_points) + floatval($points);
    }   
    
    
    public function getPointsForLoss($points)
    {
        if(floatval($points) >= $this->_offset_points)
            return 0;
        
        return floatval($this->_offset_points) - floatval($points);
    }   
    
}

?>
