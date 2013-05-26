<?php
namespace League\Statistics\Games;

/**
 * interface for game statistics
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
interface GameStatsInterface {
    
    /**
     * get number of determined games
     * @param int $playerId
     */
    public function getNumberOfGames($playerId);
}

?>
