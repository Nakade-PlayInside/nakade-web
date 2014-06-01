<?php
namespace League\Standings\Games;

/**
 * Interface GameStatsInterface
 *
 * @package League\Standings\Games
 */
interface GameStatsInterface
{

    /**
     * @param int $playerId
     */
    public function getNumberOfGames($playerId);
}
