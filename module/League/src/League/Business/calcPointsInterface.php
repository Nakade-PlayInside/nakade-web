<?php
namespace League\Business;

/**
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 * 
 */
interface calcPointsInterface {
    
     /**
     * Get point for resign (or win by forfeit) 
     * @return float
     */
    public function getPointsForResign();
    
    /**
     * Get points for skipping a game.
     * This is occasionally used in a round-based tournament.
     *  
     * @return float
     */
    public function getPointsForSkip();
    
    /**
     * Get points for win. If points exceed the offset, maximum
     * points are given.
     *  
     * @param float $points
     * @return float
     */
    public function getPointsForWin($points);
    
    /**
     * Get points for loss. If points exceed the offset, no
     * points are given.
     *  
     * @param float $points
     * @return float
     */
    public function getPointsForLoss($points);   
}

