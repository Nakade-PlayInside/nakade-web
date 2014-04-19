<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;

/**
 * Determine the number of suspended games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SuspendedGames extends AbstractGameStats implements GameStatsInterface
{

    /**
     * gets you the number a suspended games
     * @param int $playerId
     *
     * @return int
     */
    public function getNumberOfGames($playerId)
    {

        $count=0;
        foreach ($this->getMatches() as $match) {

            $blackId = $match->getBlackId();
            $whiteId = $match->getWhiteId();

            if (null === $match->getResultId() ||
               $match->getResultId() != RESULT::SUSPENDED) {
                continue;
            }

            if ($blackId==$playerId || $whiteId==$playerId) {
                $count++;
            }

        }

        return $count;
    }


}
