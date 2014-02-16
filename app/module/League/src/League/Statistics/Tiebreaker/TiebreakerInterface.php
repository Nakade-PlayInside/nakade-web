<?php
namespace League\Statistics\Tiebreaker;

/**
 * interface for tiebreakers
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
interface TiebreakerInterface {
    
    /**
     * get the tiebreaking points
     * @param int $playerId
     */
    public function getTieBreaker($playerId);
}

?>
