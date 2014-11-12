<?php
namespace Nakade\Stats;

/**
 * Interface GameStatsInterface
 *
 * @package Nakade\Stats
 */
interface GameStatsInterface
{

    /**
     * @param int $playerId
     */
    public function getNumberOfGames($playerId);
}
